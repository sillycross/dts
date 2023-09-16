<?php

namespace skill434
{
	function init() 
	{
		define('MOD_SKILL434_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[434] = '核弹';
	}
	
	function acquire434(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost434(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked434(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(434,$pa))&&(check_unlocked434($pa))&&($key=='d'))
		{
			return $chprocess($pa, $pd, $active, $key)*1.7;
		}
		return $chprocess($pa, $pd, $active, $key);
	}
}

?>
