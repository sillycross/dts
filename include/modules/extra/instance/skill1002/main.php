<?php

namespace skill1002
{
	$skill1002_act_time = 60;
	$skill1002_no_effect_array = Array(1,9,20,21,22,88);	//不免疫的npc类别
	
	function init() 
	{
		define('MOD_SKILL1002_INFO','card;locked;unique;');
		eval(import_module('clubbase'));
		$clubskillname[1002] = '无垢';
	}
	
	function acquire1002(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill1002'));
		\skillbase\skill_setvalue(1002,'expire',$now + $skill1002_act_time,$pa);
	}
	
	function lost1002(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked1002(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function check_available1002(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill1002'));
		if(!\skillbase\skill_query(1002,$pa)) return false;
		$expire = \skillbase\skill_getvalue(1002,'expire',$pa);
		if($now > $expire) return false;
		else return true;
	}
	
	//无垢状态下不能遭遇尸体
	function check_corpse_discover(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (check_available1002($sdata))
			return 0;
		else return $chprocess($edata);
	}
	
	function apply_total_damage_modifier_invincible(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(1002,$pd)) {
			$chprocess($pa,$pd,$active);
			return;
		}
		eval(import_module('sys','logger','skill1002'));
		if ($pa['dmg_dealt'] >= 100 && check_available1002($pd) && !in_array($pa['type'],$skill1002_no_effect_array)){	//scp和蓝凝无效
			$pa['dmg_dealt']=0;
			$log .= \battle\battlelog_parser($pa, $pd, $active, "<span class='yellow b'><:pd_name:>的技能「无垢」使<:pa_name:>的攻击没有造成任何伤害！</span><br>");
		}
		$chprocess($pa,$pd,$active);
	}
	
	function kill(&$pa, &$pd)	//在遇到SCP或蓝凝而死时放嘲讽
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$ret = $chprocess($pa,$pd);
		
		eval(import_module('skill1002'));
		if (in_array($pd['state'],Array(20,21,22,23,24,25,27,29)))
			if (\skillbase\skill_query(1002,$pd) && in_array($pa['type'],$skill1002_no_effect_array))
			{
				eval(import_module('sys','logger'));
				if (check_available1002($pd))
				{
					$log.="<span class=\"cyan b\">都告诉你了，无垢对某些NPC无效……快去死吧。</span><br>";
				}
			}
			
		return $ret;
	}
	
	function get_trap_final_damage_modifier_down(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(1002,$pd)) return $chprocess($pa,$pd,$tritm,$damage);
		eval(import_module('sys','logger','skill1002'));
		if ($damage >= 100 && check_available1002($pd))
		{
			$log .= "<span class=\"yellow b\">你的技能「无垢」使你免疫了陷阱伤害！</span><br>";
			return 0;
		}	
		return $chprocess($pa,$pd,$tritm,$damage);
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if (\skillbase\skill_query(1002,$sdata) && check_unlocked1002($sdata))
		{
			eval(import_module('skill1002'));
			$skill1002_time = $skill1002_act_time - (\skillbase\skill_getvalue(1002,'expire') - $now);
			$z=Array(
				'disappear' => 0,
			);
			if ($skill1002_time < $skill1002_act_time)
			{
				$z['clickable'] = 1;
				$z['style']=1;
				$z['totsec']=$skill1002_act_time;
				$z['nowsec']=$skill1002_time;
				$skill1002_rm = $skill1002_act_time-$skill1002_time;
				$z['hint'] = "技能「无垢」";
			}
			else  
			{
				$z['clickable'] = 0;
				$z['style']=3;
				$z['activate_hint'] = "技能「无垢」生效时间已经结束";
				\skillbase\skill_lost(1002);	//仅限一次，进入CD即自动失去技能
			}
			\bufficons\bufficon_show('img/skill1002.gif',$z);
		}
		$chprocess();
	}
}

?>