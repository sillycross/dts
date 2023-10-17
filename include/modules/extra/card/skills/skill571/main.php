<?php

namespace skill571
{
	function init() 
	{
		define('MOD_SKILL571_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[571] = '空壳';
	}
	
	function acquire571(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost571(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function parse_interface_profile()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		if (\skillbase\skill_query(571))
		{
			eval(import_module('sys'));
			$uip['total_att'] *= 12321;
			$uip['total_def'] *= 12321;
		}
	}
	
}

?>
