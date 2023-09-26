<?php

namespace ex_gender_attr
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['l'] = '热恋';
		$itemspkdesc['l']='攻击与自己不同性别的敌人有可能被迷惑，反之有可能被激怒';
		$itemspkremark['l']='20%概率生效。激怒时物理伤害2倍；迷惑时物理伤害变成1。';
		
		$itemspkinfo['g'] = '同志';
		$itemspkdesc['g']='攻击与自己不同性别的敌人有可能被激怒，反之有可能被迷惑';
		$itemspkremark['g']='20%概率生效。激怒时物理伤害2倍；迷惑时物理伤害变成1。';
	}
	
	function get_gender_bewitch_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//迷惑触发概率
		return 20;
	}
	
	function get_gender_enrage_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//激怒触发概率
		return 20;
	}
	
	//注意这个函数返回的是一个数组
	function check_gender_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$ex_att_array = \attrbase\get_ex_attack_array($pa, $pd, $active);
		//无对应属性直接返回
		if (!\attrbase\check_in_itmsk('l',$ex_att_array) && !\attrbase\check_in_itmsk('g',$ex_att_array)) return Array();
		//有一方无性别时直接返回
		if ($pa['gd'] === 0 || $pd['gd'] === 0) return Array(); 
		//都出现时视为l
		if (\attrbase\check_in_itmsk('l',$ex_att_array)) $ty = 1; else $ty = 0;
		if (($ty==1 && $pa['gd']!=$pd['gd']) || ($ty == 0 && $pa['gd']==$pd['gd']))
		{
			//判迷惑
			$proc_rate = get_gender_bewitch_proc_rate($pa, $pd, $active);
			$dice = rand(0,99);
			if ($dice < $proc_rate)
			{
				//迷惑触发，视为伤抹成功
				if ($active)
					$log .= "<span class=\"red b\">你被{$pd['name']}迷惑，无法全力攻击！</span><br>";
				else  $log .= "<span class=\"red b\">{$pa['name']}被你迷惑，无法全力攻击！</span><br>";
				$pd['physical_nullify_success'] = 1;
			}
		}
		else
		{
			//判激怒
			$proc_rate = get_gender_enrage_proc_rate($pa, $pd, $active);
			$dice = rand(0,99);
			if ($dice < $proc_rate)
			{
				//激怒触发，伤害*2
				if ($active)
					$log .= "<span class=\"red b\">你被{$pd['name']}激怒，伤害加倍！</span><br>";
				else  $log .= "<span class=\"red b\">{$pa['name']}被你激怒，伤害加倍！</span><br>";
				return Array(2);
			}
		}
		return Array();
	}
			
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = check_gender_proc($pa, $pd, $active);
		return array_merge($r, $chprocess($pa, $pd, $active));
	}
}

?>
