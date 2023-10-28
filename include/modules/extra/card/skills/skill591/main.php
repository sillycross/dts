<?php

namespace skill591
{
	$phydmg_gain = array(6,66,666);
	$sk591_rate = array(33,99,100);

	function init() 
	{
		define('MOD_SKILL591_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[591] = '熔毁';
	}
	
	function acquire591(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost591(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked591(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl'] >= 6;
	}
	
	function check_skill591_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill591','player','logger'));
		if (\skillbase\skill_query(591, $pa) && check_unlocked591($pa))
		{
			$dice = rand(0,99);
			$attgain = get_skill591_phydmg_gain($dice);
			if ($attgain == $phydmg_gain[0]) $log .= "<span class=\"yellow b\">";
			else if ($attgain == $phydmg_gain[1]) $log .= "<span class=\"orange b\">";
			else $log .= "<span class=\"red b\">";
			$log .= "「熔毁」使此次攻击的物理伤害增加了{$attgain}%！</span><br>";			
			$dmggain = (100 + $attgain) / 100;
			return array($dmggain);
		}
		return array();
	}
	
	function get_skill591_phydmg_gain($dice)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill591')); 
		if ($dice < $sk591_rate[0]) $attgain = $phydmg_gain[0];
		else if ($dice < $sk591_rate[1]) $attgain = $phydmg_gain[1];
		else $attgain = $phydmg_gain[2];
		return $attgain;
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = check_skill591_proc($pa,$pd,$active);
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
}

?>
