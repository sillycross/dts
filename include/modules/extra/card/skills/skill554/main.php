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
	
	function calc_memory_slotnum(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = & $sdata;
		}	
		
		$ret = $chprocess($pa);
		if (\skillbase\skill_query(554,$pa)) {
			eval(import_module('skill554'));
			$ret = min($ret, $skill554_slotnum);
		}
		return $ret;
	}
}

?>