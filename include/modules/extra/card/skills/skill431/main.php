<?php

namespace skill431
{
	function init() 
	{
		define('MOD_SKILL431_INFO','card;hidden;');
	}
	
	function acquire431(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost431(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if(\skillbase\skill_query(431)){
			$wp+=3;$wk+=3;$wc+=3;$wf+=3;$wg+=3;$wd+=2;
			\lvlctl\getexp(3);
		}
		$chprocess();
	}
		
}

?>
