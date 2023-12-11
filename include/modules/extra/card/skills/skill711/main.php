<?php

namespace skill711
{
	function init()
	{
		define('MOD_SKILL711_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[711] = '隐匿';
	}
	
	function acquire711(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost711(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//不会被发现
	function check_alive_discover(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(711, $edata))
			return 0;
		else return $chprocess($edata);
	}
	
}

?>