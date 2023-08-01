<?php

namespace ex_armor_crack
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['^ac'] = '碎甲';
		$itemspkdesc['^ac']='能对防具造成更严重的破坏';
		$itemspkremark['^ac']='致伤率上升；触发致伤时对防具额外造成耐久10%的耐久损耗。';
	}
	
	//致伤率上升30%
	function calculate_inf_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=1;
		if (\attrbase\check_in_itmsk('^ac', \attrbase\get_ex_attack_array($pa, $pd, $active))) $r*=1.3;
		return $chprocess($pa, $pd, $active)*$r;
	}
	
	//计算额外的损耗值
	function get_armor_crack_extra_wounds(&$pa, &$pd, $active, $position)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		if (empty($position) || empty($pd['ar'.$position])) return 0;
		eval(import_module('itemmain'));
		//无限耐防具则判定效果值
		if($nosta == $pd['ar'.$position]) $pdardef = $pd['ar'.$position.'e'];
		else $pdardef = $pd['ar'.$position.'s'];
		$pawepatt = $pa['wepe'];
		//损耗值是攻击方武器效果值的根号，加上防御方耐久的10%左右，并向上取整
		$extrahit_ac = ceil(sqrt($pawepatt)+$pdardef*rand(80,120)/1000); 
		return $extrahit_ac; 
	}
	
	//应用额外的损耗值
	function calculate_weapon_wound_base(&$pa, &$pd, $active, $hurtposition) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//echo '攻击者'.$pa['name'].'属性判定：'.(implode(\attrbase\get_ex_attack_array($pa, $pd, $active)));
		if (!\attrbase\check_in_itmsk('^ac', \attrbase\get_ex_attack_array($pa, $pd, $active))) return $chprocess($pa, $pd, $active, $hurtposition);
		return $chprocess($pa, $pd, $active, $hurtposition) + get_armor_crack_extra_wounds($pa, $pd, $active, $hurtposition);
	}
}

?>
