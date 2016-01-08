<?php

namespace skill241
{
	function init() 
	{
		define('MOD_SKILL241_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[241] = '推广';
	}
	
	function acquire241(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost241(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked241(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		eval(import_module('lvlctl'));
		if (\skillbase\skill_query(241,$pa) && rand(0,99)<50) $lvupskpt++;
	}
}

?>
