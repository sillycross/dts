<?php

namespace skill561
{
	function init() 
	{
		define('MOD_SKILL561_INFO','card;hidden;');
		eval(import_module('clubbase'));
	}
	
	function acquire561(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost561(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function calculate_move_sp_cost()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(561)) return $chprocess()+5;
		return $chprocess();
	}
	
	function calculate_search_sp_cost()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(561)) return $chprocess()+5;
		return $chprocess();
	}
}

?>