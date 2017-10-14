<?php

namespace skill484
{	
	function init() 
	{
		define('MOD_SKILL484_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[484] = '被削';
	}
	
	function acquire484(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost484(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked484(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_club_choice_array()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','clubbase'));
		$ret = $chprocess();
		if(\skillbase\skill_query(484)){
			if(8 != $ret[3]) $ret[2] = 8;
			if(2 != $ret[1]) $ret[1] = 2; 
		}
		return $ret;
	}
}

?>