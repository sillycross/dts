<?php

namespace skill903
{
	function init() 
	{
		define('MOD_SKILL903_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[903] = '厄运';
	}
	
	function acquire903(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(903,'lvl','0',$pa);
	}
	
	function lost903(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked903(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calculate_real_trap_obbs()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(903) && ((int)\skillbase\skill_getvalue(903, 'lvl') >= 1)) return $chprocess()*1.2;
		else return $chprocess();
	}

	function calculate_itemfind_obbs_multiplier()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(903) && ((int)\skillbase\skill_getvalue(903, 'lvl') >= 2)) return 0.8*$chprocess();
		else return $chprocess();
	}
	
	function calculate_meetman_rate($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(903) && ((int)\skillbase\skill_getvalue(903, 'lvl') >= 2)) return 0.8*$chprocess($schmode);
		else return $chprocess($schmode);
	}

	function get_ex_phy_nullify_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(903, $pd) && ((int)\skillbase\skill_getvalue(903, 'lvl', $pd) >= 4)) $ret = 90;
		return $ret;
	}
	
	function get_ex_dmg_nullify_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(903, $pd) && ((int)\skillbase\skill_getvalue(903, 'lvl', $pd) >= 4)) $ret = 90;
		return $ret;
	}
	
	function get_dmg_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(903, $pd) && ((int)\skillbase\skill_getvalue(903, 'lvl', $pd) >= 4)) $ret = 85;
		return $ret;
	}
	
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(903, $ldata) && ((int)\skillbase\skill_getvalue(903, 'lvl', $ldata) >= 5)) $r *= 0.8;
		return $chprocess($ldata,$edata)*$r;
	}
	
	function get_hitrate_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(903, $pa) && ((int)\skillbase\skill_getvalue(903, 'lvl', $pa) >= 5)) $r *= 0.8;
		return $chprocess($pa, $pd, $active)*$r;
	}
	
}

?>