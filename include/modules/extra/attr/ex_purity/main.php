<?php

namespace ex_purity
{
	
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['Y'] = '纯粹';
		$itemspkdesc['Y'] = '攻击时其他攻击属性全部无效，无视对方装备的所有减半防御属性';		
		$itemspkremark['Y'] = '无视自身所有其他攻击属性；<br>无视对方防御属性列表：防殴、防斩、防弹、防投、防爆、防灵、防连、物防';
	}

	//攻击时其他攻击属性全部无效
	function get_ex_attack_array(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);	
		if (in_array('Y', $ret)) {
			$pa['purity'] = 1;
			$ret = array_diff($ret, array('S','r','d','p','u','i','e','w','f','k','t','n','y','N','^ac','L','v','V'));
		}
		return $ret;
	}

	function check_phy_pierce_proc(&$pa, &$pd, $active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//纯粹伤害跳过物穿判定
		if (!empty($pa['purity'])) {
			eval(import_module('logger'));
			$log .= \battle\battlelog_parser($pa, $pd, $active, '<span class="white b"><:pa_name:>释放出了纯粹的能量，穿透了<:pd_name:>的防御！</span><br>');
//			if ($active) $log .= "<span class=\"white b\">你的攻击释放出了纯粹的能量，穿透了敌人的防御！</span><br>";
//			else $log .= "<span class=\"white b\">敌人的攻击释放出了纯粹的能量，穿透了你的防御！</span><br>";
			return;
		}
		$chprocess($pa,$pd,$active);
	}
	
	function check_attr_pierce_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//纯粹伤害跳过属穿判定
		if (!empty($pa['purity'])) return;
		$chprocess($pa,$pd,$active);
	}	
	
	//可能有技能增加连击数？
	function check_ex_rapid_def_exists(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret =  $chprocess($pa, $pd, $active);
		//纯粹伤害跳过防连判定
		if (!empty($pa['purity'])) $ret = 0;
		return $ret;
	}	

	function check_physical_def_attr(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//纯粹伤害跳过减半属性判定
		if (!empty($pa['purity'])) return Array();
		return $chprocess($pa, $pd, $active);
	}
}

?>