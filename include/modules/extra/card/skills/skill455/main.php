<?php

namespace skill455
{
	$skill455_act_time = 360;
	$skill455_no_effect_array = Array(1,9,20,21,22,88);	//不免疫的npc类别
	
	function init() 
	{
		define('MOD_SKILL455_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[455] = '无敌';
	}
	
	function acquire455(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost455(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked455(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function apply_total_damage_modifier_invincible(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(455,$pd)) {
			$chprocess($pa,$pd,$active);
			return;
		}
		eval(import_module('sys','logger','skill455'));
		$x=$now-$starttime;
		if ($x<=$skill455_act_time && !in_array($pa['type'],$skill455_no_effect_array)){	//scp和蓝凝无效
			$pa['dmg_dealt']=0;
			if ($active) $log .= "<span class=\"yellow b\">敌人的技能「无敌」使你的攻击没有造成任何伤害！</span><br>";
			else $log .= "<span class=\"yellow b\">你的技能「无敌」使敌人的攻击没有造成任何伤害！</span><br>";
		}
		$chprocess($pa,$pd,$active);
	}
	
	function kill(&$pa, &$pd)	//在遇到SCP或蓝凝而死时放嘲讽
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$ret = $chprocess($pa,$pd);
		
		eval(import_module('skill455'));
		if (in_array($pd['state'],Array(20,21,22,23,24,25,27,29)))
			if (\skillbase\skill_query(455,$pd) && in_array($pa['type'],$skill455_no_effect_array))
			{
				eval(import_module('sys','logger'));
				$x=$now-$starttime;
				if ($x<=$skill455_act_time)
				{
					$log.="<span class=\"cyan b\">都告诉你了，无敌对某些NPC无效……快去死吧。</span><br>";
				}
			}
		return $ret;
	}
	
	function get_trap_final_damage_modifier_down(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(455,$pd)) return $chprocess($pa,$pd,$tritm,$damage);
		eval(import_module('sys','logger','skill455'));
		$x=$now-$starttime;
		if ($x<=$skill455_act_time)
		{
			$log .= "<span class=\"yellow b\">你的技能「无敌」使你免疫了陷阱伤害！</span><br>";
			return 0;
		}	
		return $chprocess($pa,$pd,$tritm,$damage);
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(455,$sdata))&&check_unlocked455($sdata))
		{
			eval(import_module('skill455'));
			$skill455_time = $now-$starttime; 
			$z=Array(
				'disappear' => 0,
			);
			if ($skill455_time<$skill455_act_time)
			{
				$z['clickable'] = 1;
				$z['style']=1;
				$z['totsec']=$skill455_act_time;
				$z['nowsec']=$skill455_time;
				$skill455_rm = $skill455_act_time-$skill455_time;
				$z['hint'] = "技能「无敌」";
			}
			else  
			{
				$z['clickable'] = 0;
				$z['style']=3;
				$z['activate_hint'] = "技能「无敌」生效时间已经结束";
			}
			\bufficons\bufficon_show('img/skill455.gif',$z);
		}
		$chprocess();
	}
}

?>
