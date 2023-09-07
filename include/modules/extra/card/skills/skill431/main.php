<?php

namespace skill431
{
	$skill431_skillup = 4;
	$skill431_expup = 6;
	
	function init() 
	{
		define('MOD_SKILL431_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[431] = '巧手';
	}
	
	function acquire431(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost431(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked431(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(431)){
			eval(import_module('sys','player','skill431'));
			foreach(Array('wp','wk','wc','wg','wd','wf') as $v){
				if('wd' == $v) ${$v} += $skill431_skillup - 1;
				else ${$v} += $skill431_skillup;
			}
			\lvlctl\getexp($skill431_expup);
		}
		$chprocess();
	}
		
}

?>