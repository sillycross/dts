<?php

namespace skill432
{
	
	function init() 
	{
		define('MOD_SKILL432_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[432] = '凝心';
	}
	
	function acquire432(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost432(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function check_unlocked432(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calculate_hp_rev_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(432,$pa))&&(check_unlocked432($pa))){
			return 0;
		}
		return $chprocess($pa,$pd,$active);
	}
	
	//被攻击时双方都无法增长怒气
	function calculate_attack_rage_gain_change(&$pa, &$pd, $active, $rageup)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $rageup);
		if (\skillbase\skill_query(432,$pd) && check_unlocked432($pd)) {
			$ret = 0;
		}
		return $ret;
	}
}

?>
