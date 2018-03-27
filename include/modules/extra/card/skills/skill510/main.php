<?php

namespace skill510
{	
	function init() 
	{
		define('MOD_SKILL510_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[510] = '无懈';
	}
	
	function acquire510(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost510(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//如果斩杀技能存在，在斩杀触发时失效
	function check_unlocked510(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$flag = 1;
		if(\skillbase\skill_query(507,$pa) && \skill507\check_unlocked507($pa)) $flag = 0;
		return $flag;
	}
	
	//贯穿触发率
	function get_ex_pierce_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(510,$pd) && \skill510\check_unlocked510($pd)) $ret /= 10;
		return $ret;
	}
	
	//属穿触发率
	function get_attr_pierce_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(510,$pd) && \skill510\check_unlocked510($pd)) $ret /= 10;
		return $ret;
	}
	
	function get_ex_dmg_def_proc_rate(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $key);
		if(\skillbase\skill_query(510,$pd) && \skill510\check_unlocked510($pd)) $ret = 100;
		return $ret;
	}
	
	function get_ex_phy_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(510,$pd) && \skill510\check_unlocked510($pd)) $ret = 100;
		return $ret;
	}
	
	//伤害制御效果发生率
	function get_dmg_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(510,$pd) && \skill510\check_unlocked510($pd)) $ret = 100;
		return $ret;
	}
}

?>