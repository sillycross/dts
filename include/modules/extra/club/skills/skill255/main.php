<?php

namespace skill255
{
	function init() 
	{
		define('MOD_SKILL255_INFO','club;feature;');
		eval(import_module('clubbase'));
		$clubskillname[255] = '疾风';
		$clubdesc_h[6] = $clubdesc_a[6] = '随着等级上升，移动和探索消耗的体力大幅减少（最少为1）<br>陷阱遭遇率-15%，陷阱回避率+10%';
	}
	
	function acquire255(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost255(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked255(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calculate_move_sp_cost()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = $chprocess();
		if (\skillbase\skill_query(255)) 
		{
			eval(import_module('player'));
			$r-=10+round($lvl/4);
		}
		return $r;
	}
	
	function calculate_search_sp_cost()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = $chprocess();
		if (\skillbase\skill_query(255)) 
		{
			eval(import_module('player'));
			$r-=10+round($lvl/4);
		}
		return $r;
	}
	
	function calculate_real_trap_obbs()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = $chprocess();
		if (\skillbase\skill_query(255)) $r*=0.85;
		return $r;
	}
	
	function get_trap_escape_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = $chprocess();
		if (\skillbase\skill_query(255)) $r*=1.1;
		return $r;
	}
}

?>
