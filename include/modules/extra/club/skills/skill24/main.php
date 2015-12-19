<?php

namespace skill24
{
	function init() 
	{
		define('MOD_SKILL24_INFO','club;hidden;');
	}
	
	function acquire24(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost24(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('lvlctl'));
		if (rand(0,2)==0) $lvuphp += 2; else $lvuphp += 1;
		$chprocess($pa);
	}
}

?>
