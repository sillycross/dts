<?php

namespace skill254
{
	function init() 
	{
		define('MOD_SKILL254_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[254] = '天义';
	}
	
	function acquire254(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost254(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked254(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=15;
	}
	
	function get_ex_phy_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(254,$pa) || !check_unlocked254($pa)) return $chprocess($pa, $pd, $active);
		return max(100-max(100-$chprocess($pa, $pd, $active),0)*3,0);
	}
	
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(254,$pa) && check_unlocked254($pa)) array_push($ret,'N');
		return $ret;
	}
}

?>
