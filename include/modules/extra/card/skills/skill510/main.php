<?php

namespace skill510
{	
	//1: 玩家 2: 特殊NPC
	$phydef510 = array( 1 => 100, 2 => 100);
	$exdef510 = array( 1 => 100, 2 => 100);
	$phynullify510 = array( 1 => 100, 2 => 100);
	$exnullify510 = array( 1 => 100, 2 => 100);
	$phypierce510 = array( 1 => 0.5, 2 => 0.1);//系数
	$expierce510 = array( 1 => 0.5, 2 => 0.1);//系数
	$hpdef510 = array( 1 => 95, 2 => 100);
	
	function init() 
	{
		define('MOD_SKILL510_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[510] = '胜天';
	}
	
	function acquire510(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(510,'lvl','0',$pa);
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
	
	function get_rate510($kind, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill510'));
		$lvl = \skillbase\skill_getvalue(510,'lvl',$pa);
		if($lvl && isset(${$kind.'510'}[$lvl])) {
			//echo $kind.':'.${$kind.'510'}[$lvl];
			return ${$kind.'510'}[$lvl];
		}
		return NULL;
	}
	
	//贯穿触发率
	function get_ex_pierce_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(510,$pd) && \skill510\check_unlocked510($pd)) {
			$f = get_rate510('phypierce', $pd);
			if(NULL !== $f) $ret *= $f;
		}
		return $ret;
	}
	
	//属穿触发率
	function get_attr_pierce_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(510,$pd) && \skill510\check_unlocked510($pd)) {
			$f = get_rate510('expierce', $pd);
			if(NULL !== $f) $ret *= $f;
		}
		return $ret;
	}
	
	function get_ex_dmg_def_proc_rate(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $key);
		if(\skillbase\skill_query(510,$pd) && \skill510\check_unlocked510($pd)) {
			$r = get_rate510('exdef', $pd);
			if(NULL !== $r) $ret = $r;
		}
		return $ret;
	}
	
	function get_ex_phy_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(510,$pd) && \skill510\check_unlocked510($pd)) {
			$r = get_rate510('phydef', $pd);
			if(NULL !== $r) $ret = $r;
		}
		return $ret;
	}
	
	function get_ex_phy_nullify_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(510,$pd) && \skill510\check_unlocked510($pd)) {
			$r = get_rate510('phynullify', $pd);
			if(NULL !== $r) $ret = $r;
		}
		return $ret;
	}
	
	function get_ex_dmg_nullify_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(510,$pd) && \skill510\check_unlocked510($pd)) {
			$r = get_rate510('exnullify', $pd);
			if(NULL !== $r) $ret = $r;
		}
		return $ret;
	}
	
	//伤害制御效果发生率
	function get_dmg_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(510,$pd) && \skill510\check_unlocked510($pd)) {
			$r = get_rate510('hpdef', $pd);
			if(NULL !== $r) $ret = $r;
		}
		return $ret;
	}
	
	
}

?>