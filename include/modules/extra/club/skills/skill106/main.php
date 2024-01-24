<?php

namespace skill106
{
	function init()
	{
		define('MOD_SKILL106_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[106] = '虹光';//技能的实际处理在skill272模块中
	}
	
	function acquire106(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost106(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked106(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \skillbase\skill_getvalue(272,'lvl',$pa);
	}
	
}

?>