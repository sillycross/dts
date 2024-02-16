<?php

namespace skill251
{
	$skill251_act_time = 5;
	$skill251_no_effect_array = Array(1,9,20,21,22,88);
	
	function init() 
	{
		define('MOD_SKILL251_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[251] = '天佑';
	}
	
	function acquire251(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(251,'start',0,$pa);
	}
	
	function lost251(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked251(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function apply_total_damage_modifier_invincible(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(251,$pd)) {
			$chprocess($pa,$pd,$active);
			return;
		}
		eval(import_module('sys','logger','skill251'));
		$s=(int)\skillbase\skill_getvalue(251,'start',$pd);
		$x=$now-$s;
		if ($x<=$skill251_act_time && !in_array($pa['type'],$skill251_no_effect_array)){	//scp和蓝凝无效
			$pa['dmg_dealt']=0;
			if ($active) $log .= "<span class=\"yellow b\">敌人的技能「天佑」使你的攻击没有造成任何伤害！</span><br>";
			else $log .= "<span class=\"yellow b\">你的技能「天佑」使敌人的攻击没有造成任何伤害！</span><br>";
		}
		$chprocess($pa,$pd,$active);
	}
	
	function kill(&$pa, &$pd)	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$ret = $chprocess($pa,$pd);
		
		eval(import_module('skill251'));
		if (in_array($pd['state'],Array(20,21,22,23,24,25,27,29)))
			if (\skillbase\skill_query(251,$pd) && in_array($pa['type'],$skill251_no_effect_array))
			{
				eval(import_module('sys','logger'));
				$s=(int)\skillbase\skill_getvalue(251,'start',$pd);
				$x=$now-$s;
				if ($x<=$skill251_act_time)
				{
					$log.="<span class=\"cyan b\">都告诉你了，无敌对某些NPC无效……快去死吧。</span><br>";
				}
			}
		
		return $ret;
	}
	
	function get_trap_final_damage_modifier_down(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(251,$pd)) return $chprocess($pa,$pd,$tritm,$damage);
		eval(import_module('sys','logger','skill251'));
		$s=(int)\skillbase\skill_getvalue(251,'start',$pd);
		$x=$now-$s;
		if ($x<=$skill251_act_time)
		{
			$log .= "<span class=\"yellow b\">你的技能「天佑」使你免疫了陷阱伤害！</span><br>";
			return 0;
		}	
		return $chprocess($pa,$pd,$tritm,$damage);
	}
	
	function apply_damage(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(251,$pd) && $pa['dmg_dealt']>=$pd['mhp']*0.35 && $pd['hp']>0)
		{
			eval(import_module('sys','logger'));
			if ($active) 
				$log .= '<span class="yellow b">敌人的技能「天佑」被触发，暂时进入了无敌状态。</span><br>';
			else  $log .= '<span class="yellow b">你的技能「天佑」被触发，暂时进入了无敌状态！</span><br>';
			\skillbase\skill_setvalue(251,'start',$now,$pd);
		}
		return $ret;
	}
	
	function post_traphit_events($pa, $sdata, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(251,$sdata) && $damage>=$sdata['mhp']*0.35 && $sdata['hp']>0)
		{
			eval(import_module('sys','logger'));
			//$log .= $damage.' '.$sdata['mhp'].'<br>';
			$log .= '<span class="yellow b">你的技能「天佑」被触发，暂时进入了无敌状态！</span><br>';
			\skillbase\skill_setvalue(251,'start',$now);
		}
		$chprocess($pa, $sdata, $tritm, $damage);
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(251,$sdata))&&check_unlocked251($sdata))
		{
			eval(import_module('skill251'));
			$s=(int)\skillbase\skill_getvalue(251,'start',$pd);
			$skill251_time = $now-$s; 
			$z=Array(
				'disappear' => 0,
			);
			if ($skill251_time<$skill251_act_time)
			{
				$z['clickable'] = 1;
				$z['style']=1;
				$z['totsec']=$skill251_act_time;
				$z['nowsec']=$skill251_time;
				$skill251_rm = $skill251_act_time-$skill251_time;
				$z['hint'] = "技能「天佑」生效中<br>免疫一切战斗或陷阱伤害";
			}
			else  
			{
				$z['clickable'] = 0;
				$z['style']=3;
				$z['activate_hint'] = "技能「天佑」";
			}
			\bufficons\bufficon_show('img/skill251.gif',$z);
		}
		$chprocess();
	}
}

?>
