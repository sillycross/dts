<?php

namespace skill19
{
	function init() 
	{
		define('MOD_SKILL19_INFO','club;hidden;');
	}
	
	function acquire19(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost19(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function get_trap_escape_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(19)) return $chprocess()+17; else return $chprocess();
	}
	
	function calculate_trap_reuse_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(19)) return $chprocess()+35; else return $chprocess();
	}
	
	function trap_use(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(19)) 
		{
			eval(import_module('player'));
			$wd+=rand(1,2); \lvlctl\getexp(rand(1,2));
		}
		$chprocess($theitem);
	}
}

?>
