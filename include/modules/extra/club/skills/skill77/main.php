<?php

namespace skill77
{
	function init() 
	{
		define('MOD_SKILL77_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[77] = '觉醒';
	}
	
	function acquire77(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost77(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked77(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=19;
	}
}

?>
