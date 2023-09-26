<?php

namespace ex_purity
{
	//被本属性认为是攻击属性的有：消音 连击 爆炸 毒 烧 冻 电 音 灼焰 冰华 音爆 物穿 属穿 冲击 碎甲 直击 直死 弑神
	$attack_attr_purified = Array('S','r','d','p','u','i','e','w','f','k','t','n','y','N','^ac','L','v','V');
	
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['Y'] = '纯粹';
		$itemspkdesc['Y'] = '攻击时其他攻击属性全部无效，无视对方装备的所有物理防御属性';		
		$itemspkremark['Y'] = '无视自身所有其他攻击属性；<br>无视对方物理防御属性列表：物防、防殴、防斩、防弹、防投、防爆、防灵、防连';
	}

	//攻击属性全部无效直到战斗结束
	function get_ex_attack_array(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);	
		if (!empty($pa['purity'])) {
			eval(import_module('ex_purity'));
			$ret = array_diff($ret, $attack_attr_purified);
		}
		return $ret;
	}
	
	//攻击准备阶段，给攻击者做个标记：如果攻击者有纯粹属性，那么攻击者自身的所有攻击属性全部无效
	function attack_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if (\attrbase\check_in_itmsk('Y', \attrbase\get_ex_attack_array($pa, $pd, $active))) {
			$pa['purity'] = 1;
			eval(import_module('logger'));
			$log .= \battle\battlelog_parser($pa, $pd, $active, '<span class="white b"><:pa_name:>将所有其他属性汇聚成一股纯粹的能量！</span><br>');
		}
	}	

	function check_phy_pierce_proc(&$pa, &$pd, $active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//纯粹伤害跳过物穿判定
		if (!empty($pa['purity'])) {
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
		if (!empty($pa['purity'])) {
			return;
		}
		$chprocess($pa,$pd,$active);
	}	
	
	//可能有技能增加连击数？
	function check_ex_rapid_def_exists(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		//暂存$log用于覆盖防连提示
		$tmp_log = $log;
		$ret =  $chprocess($pa, $pd, $active);
		//纯粹伤害跳过防连判定
		if (!empty($ret) && !empty($pa['purity'])) {
			if(empty($pa['purity_logged'])){				
				$log = $tmp_log . \battle\battlelog_parser($pa, $pd, $active, '<span class="white b"><:pa_name:>释放出的纯粹能量穿透了<:pd_name:>的防御！</span><br>');
				$pa['purity_logged'] = 1;
			}
			$ret = 0;
		}
		unset($tmp_log);
		return $ret;
	}	

	function check_physical_def_attr(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		//暂存$log用于覆盖防御提示
		$tmp_log = $log;
		$ret = $chprocess($pa, $pd, $active);
		//纯粹伤害跳过物理减半属性判定
		if (!empty($ret) && !empty($pa['purity'])) {
			if(empty($pa['purity_logged'])){
				$log = $tmp_log . \battle\battlelog_parser($pa, $pd, $active, '<span class="white b"><:pa_name:>释放出的纯粹能量穿透了<:pd_name:>的防御！</span><br>');
				$pa['purity_logged'] = 1;
			}
			$ret = Array();
		}
		unset($tmp_log);
		return $ret;
	}
}

?>