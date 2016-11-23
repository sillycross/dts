<?php

namespace skill475
{
	function init() 
	{
		define('MOD_SKILL474_INFO','club;unique;');
		eval(import_module('clubbase'));
		$clubskillname[474] = '厌食';
	}
	
	function acquire474(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost474(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked474(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_edible_hpup(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(474,$pa)) return round($chprocess($theitem)*0.7);
		return $chprocess($theitem);
	}
	
	function get_edible_spup(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(474,$pa)) return round($chprocess($theitem)*0.7);
		return $chprocess($theitem);
	}
		
}

?>
