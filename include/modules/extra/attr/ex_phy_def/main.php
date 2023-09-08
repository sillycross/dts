<?php

namespace ex_phy_def
{
	global $def_kind;
	
	//各类武器对应的防御列表
	//全系防御的作用是防御所有在下列列表中的武器
	$def_kind = Array(
		'P' => 'P',
		'K' => 'K',
		'G' => 'G',
		'C' => 'C',
		'D' => 'D',
		'F' => 'F',
		'J' => 'G',//重枪当做射系
		'B' => 'C'//弓当做投系
	);
	
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['N'] = '防拳';
		$itemspkdesc['N']='受到空手攻击的物理伤害减半';
		$itemspkremark['N']='10%概率失效';
		
		$itemspkinfo['P'] = '防殴';
		$itemspkdesc['P']='受到钝器物理伤害减半';
		$itemspkremark['P']='10%概率失效';
		
		$itemspkinfo['K'] = '防斩';
		$itemspkdesc['K']='受到锐器物理伤害减半';
		$itemspkremark['K']='10%概率失效';
		
		$itemspkinfo['G'] = '防弹';
		$itemspkdesc['G']='受到远程武器和重型枪械物理伤害减半';
		$itemspkremark['G']='10%概率失效';
		
		$itemspkinfo['C'] = '防投';
		$itemspkdesc['C']='受到投掷武器和弓箭的物理伤害减半';
		$itemspkremark['C']='10%概率失效';
		
		$itemspkinfo['D'] = '防爆';
//		$itemspkdesc['D']='受到爆炸物物理伤害减半';
//		$itemspkremark['D']='10%概率失效';
		
		$itemspkinfo['F'] = '防符';
		$itemspkdesc['F']='受到灵力武器物理伤害减半';
		$itemspkremark['F']='10%概率失效';
		
		$itemspkinfo['A'] = '物防';
		$itemspkdesc['A']='所有攻击方式的物理伤害减半';
		$itemspkremark['A']='10%概率失效；注意：与防爆不同，物防不能防御爆炸属性伤害。';
	}
	
	function get_ex_phy_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 90;
	}
	
	function check_ex_phy_def_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$proc_rate = get_ex_phy_def_proc_rate($pa, $pd, $active);
		$dice = rand(0,99);
		return ($dice<$proc_rate);
	}
	
	//注意这个函数返回的必须是一个数组
	function check_physical_def_attr(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_phy_def','logger'));
		if (isset($def_kind[$pa['wep_kind']])) 
		{
			$ex_def_array = \attrbase\get_ex_def_array($pa, $pd, $active);
			if (in_array($def_kind[$pa['wep_kind']], $ex_def_array) || in_array('A', $ex_def_array))
			{
				if (check_ex_phy_def_proc($pa,$pd,$active))
				{
					if ($active)
						$log .= "<span class=\"yellow b\">{$pd['name']}的装备使你的攻击伤害减半了！</span><br>";
					else  $log .= "<span class=\"yellow b\">你的装备使{$pa['name']}的攻击伤害减半了！</span><br>";
					return Array(0.5);
				}
				else
				{
					if ($active)
						$log .= "<span class=\"red b\">{$pd['name']}的装备没能发挥攻击伤害减半的效果！</span><br>";
					else  $log .= "<span class=\"red b\">你的装备没能发挥攻击伤害减半的效果！</span><br>";
					return Array();
				}
			}
		}
		return Array();
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return array_merge(check_physical_def_attr($pa, $pd, $active), $chprocess($pa, $pd, $active));
	}
}

?>
