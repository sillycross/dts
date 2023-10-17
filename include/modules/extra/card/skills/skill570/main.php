<?php

namespace skill570
{
	function init() 
	{
		define('MOD_SKILL570_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[570] = '入道';
	}
	
	function acquire570(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost570(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function get_max_rage(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(570, $pa)) return 255;
		return $chprocess($pa);
	}
	
}

?>
