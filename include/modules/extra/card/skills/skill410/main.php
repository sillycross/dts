<?php

namespace skill410
{
	
	function init() 
	{
		define('MOD_SKILL410_INFO','club;unique;locked;');
		eval(import_module('clubbase'));
		$clubskillname[410] = '猛风';
	}
	
	function acquire410(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost410(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked410(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calculate_attack_rage_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(410,$pd) || !check_unlocked410($pd)) return $chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)+4;
	}
	
	function calculate_search_sp_cost()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(410))&&(check_unlocked410())) return $chprocess()-12;
		return $chprocess();
	}
}

?>
