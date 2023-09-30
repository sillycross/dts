<?php

namespace skill563
{
	function init() 
	{
		define('MOD_SKILL563_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[563] = '专长';
	}
	
	function acquire563(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (0 !== $pa['club']) \skill559\sk559_getnewclass($pa, 'club'.$pa['club']);
	}
	
	function lost563(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function club_acquire($clubid, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($clubid, $pa);
		if (\skillbase\skill_query(563)) \skill559\sk559_getnewclass($pa, 'club'.$clubid);
	}

}

?>