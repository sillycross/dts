<?php

namespace skill256
{
	function init() 
	{
		define('MOD_SKILL256_INFO','club;hidden;');
	}
	
	function acquire256(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['wp']+=50;
	}
	
	function lost256(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
}

?>
