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
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
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
