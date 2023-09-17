<?php

namespace skill554
{
	$skill554_slotnum = 2;
	
	function init() 
	{
		define('MOD_SKILL554_INFO','card;hidden;');
	}
	
	function acquire554(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost554(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function calc_memory_slotnum()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','searchmemory','skill554'));
		$ret = $chprocess();
		if (\skillbase\skill_query(554)) $ret = min($ret, $skill554_slotnum);
		return $ret;
	}
}

?>