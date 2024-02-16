<?php

namespace skill499
{
	$skill499_act_time = 110;
	$skill499_no_effect_array = Array();//Array(1,9,20,21,22,88);	//不免疫的npc类别
	
	function init() 
	{
		define('MOD_SKILL499_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[499] = '决然';
	}
	
	function acquire499(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill499'));
		\skillbase\skill_setvalue(499,'expire',$now + $skill499_act_time,$pa);
	}
	
	function lost499(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked499(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function check_available499(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill499'));
		if(!\skillbase\skill_query(499,$pa)) return false;
		$expire = \skillbase\skill_getvalue(499,'expire',$pa);
		if($now > $expire) return false;
		else return true;
	}
	
	function apply_total_damage_modifier_invincible(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(499,$pd)) {
			$chprocess($pa,$pd,$active);
			return;
		}
		eval(import_module('sys','logger','skill499'));
		if (check_available499($pd) && !in_array($pa['type'],$skill499_no_effect_array)){	//scp和蓝凝无效
			$pa['dmg_dealt']=0;
			$log .= \battle\battlelog_parser($pa, $pd, $active, "<span class='yellow b'><:pd_name:>的技能「决然」使<:pa_name:>的攻击没有造成任何伤害！</span><br>");
		}
		$chprocess($pa,$pd,$active);
	}
	
	function get_trap_final_damage_modifier_down(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(499,$pd)) return $chprocess($pa,$pd,$tritm,$damage);
		eval(import_module('sys','logger','skill499'));
		if ($damage >= 100 && check_available499($pd))
		{
			$log .= "<span class=\"yellow b\">你的技能「决然」使你免疫了陷阱伤害！</span><br>";
			return 0;
		}	
		return $chprocess($pa,$pd,$tritm,$damage);
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if (\skillbase\skill_query(499,$sdata) && check_unlocked499($sdata))
		{
			eval(import_module('skill499'));
			$skill499_time = $skill499_act_time - (\skillbase\skill_getvalue(499,'expire') - $now);
			$z=Array(
				'disappear' => 0,
			);
			if ($skill499_time < $skill499_act_time)
			{
				$z['clickable'] = 1;
				$z['style']=1;
				$z['totsec']=$skill499_act_time;
				$z['nowsec']=$skill499_time;
				$skill499_rm = $skill499_act_time-$skill499_time;
				$z['hint'] = "技能「决然」";
			}
			else  
			{
				$z['clickable'] = 0;
				$z['style']=3;
				$z['activate_hint'] = "技能「决然」生效时间已经结束";
				\skillbase\skill_lost(499);	//仅限一次，进入CD即自动失去技能
			}
			\bufficons\bufficon_show('img/skill499.gif',$z);
		}
		$chprocess();
	}
}

?>