<?php

namespace skill80
{
	function init() 
	{
		define('MOD_SKILL80_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[80] = '脑力';
		//最强大脑的特性显示实际上用的是这个技能
	}
	
	function acquire80(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost80(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked80(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	
}

?>