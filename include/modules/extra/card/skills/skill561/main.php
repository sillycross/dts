<?php

namespace skill561
{
	$skill561_spcost = 35;
	$skill561_spcost_2 = 15;

	function init() 
	{
		define('MOD_SKILL561_INFO','card;debuff;');
		eval(import_module('clubbase'));
		$clubskillname[561] = '断腿';
	}
	
	function acquire561(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost561(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function check_unlocked561(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return true;
	}

	function get_spcost561(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(557, $pa)) return get_var_in_module('skill561_spcost_2', 'skill561');
		else return get_var_in_module('skill561_spcost', 'skill561');
	}
	
	function calculate_move_sp_cost()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(561)) return $chprocess() + get_spcost561();
		return $chprocess();
	}
	
	function calculate_search_sp_cost()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(561)) return $chprocess() + get_spcost561();
		return $chprocess();
	}
}

?>