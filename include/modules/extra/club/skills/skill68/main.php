<?php

namespace skill68
{
	function init() 
	{
		define('MOD_SKILL68_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[68] = '网购';
	}
	
	function acquire68(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost68(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked68(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function check_in_shop_area($p)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(68)) return 1;
		return $chprocess($p);
	}
	
}

?>
