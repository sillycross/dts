<?php

namespace skill566
{
	function init() 
	{
		define('MOD_SKILL566_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[566] = '门神';
	}
	
	function acquire566(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost566(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked566(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function check_unlocked34(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(566,$pa)) return 1;
		return $chprocess($pa);
	}
	
	function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(566,$pd)) return $chprocess($pa, $pd, $active) * 1.2;
		return $chprocess($pa, $pd, $active);
	}

}

?>