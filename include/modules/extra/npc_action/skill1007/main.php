<?php

namespace skill1007
{
	function init() 
	{
		define('MOD_SKILL1007_INFO','hidden;');
		eval(import_module('clubbase'));
		$clubskillname[1007] = '自律';
	}
	
	function acquire1007(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost1007(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked1007(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
}

?>