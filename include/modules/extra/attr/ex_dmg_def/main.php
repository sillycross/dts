<?php

namespace ex_dmg_def
{
	global $def_kind;
	
	//各类攻击属性对应的防御列表
	//属性防御的作用是防御所有在下列列表中的属性
	$def_kind = Array(
		'p' => 'q',
		'u' => 'U',
		'i' => 'I',
		'e' => 'E',
		'w' => 'W',
		'd' => 'D',
	);
	
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['q'] = '防毒';
		$itemspkdesc['q']='受到带毒属性伤害减半，且不会中毒';
		$itemspkremark['q']='10%概率失效。注意：本属性不会免疫毒补给';
		
		$itemspkinfo['U'] = '防火';
		$itemspkdesc['U']='受到火焰属性伤害减半，且不会烧伤';
		$itemspkremark['U']='10%概率失效。不能防御灼焰';
		
		$itemspkinfo['I'] = '防冻';
		$itemspkdesc['I']='受到冻气属性伤害减半，且不会冻结';
		$itemspkremark['I']='10%概率失效。不能防御冰华';
		
		$itemspkinfo['E'] = '绝缘';
		$itemspkdesc['E']='受到电气属性伤害减半，且不会身体麻痹';
		$itemspkremark['E']='10%概率失效';
		
		$itemspkinfo['W'] = '隔音';
		$itemspkdesc['W']='受到音波属性伤害减半，且不会混乱';
		$itemspkremark['W']='10%概率失效。不能防御音爆';
		
		$itemspkinfo['D'] = '防爆';
		$itemspkdesc['D']='受到爆炸物的物理伤害、爆炸属性伤害皆减半。';
		$itemspkremark['D']='10%概率失效';
		
		$itemspkinfo['a'] = '属防';
		$itemspkdesc['a']='所有下位属性伤害减半，且不会遭受对应的异常状态。';
		$itemspkremark['a']='防御列表：带毒、火焰、冻气、电击、音波、爆炸；10%概率失效';
	}
	
	function get_ex_dmg_def_proc_rate(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 90;
	}
	
	function check_ex_dmg_def_proc(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$proc_rate = get_ex_dmg_def_proc_rate($pa, $pd, $active, $key);
		$dice = rand(0,99);
		return ($dice<$proc_rate);
	}
	
	function check_ex_single_dmg_def_attr(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att','ex_dmg_def','logger'));
		$r = 1;
		if (isset($def_kind[$key])) 
		{
			$ex_def_array = \attrbase\get_ex_def_array($pa, $pd, $active);
			if (\attrbase\check_in_itmsk($def_kind[$key], $ex_def_array) || \attrbase\check_in_itmsk('a', $ex_def_array))
			{
				if (check_ex_dmg_def_proc($pa, $pd, $active, $key))
				{
					$log .= $exdmgname[$key].'<span class="yellow b">被防具防御了！</span>';
					$r = 0.5;
					$pd['ex_dmg_'.$key.'_defend_success'] = 1;
				}
				else
				{
					$log .= '<span class="red b">属性防御装备没能发挥应有的作用！</span>';
				}
			}
		}
		return $r;
	}
	
	function calculate_ex_inf_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//如果刚才防御成功了，不再受已有异常状态加减成影响
		if ($pd['ex_dmg_'.$key.'_defend_success'] == 1) return 1.0;
		return $chprocess($pa, $pd, $active, $key);
	}
	
	function check_ex_inf_infliction(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//如果刚才防御成功了，不会中异常状态
		if ($pd['ex_dmg_'.$key.'_defend_success'] == 1) return;
		$chprocess($pa, $pd, $active, $key);
	}
	
	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = check_ex_single_dmg_def_attr($pa, $pd, $active, $key);
		return $r*$chprocess($pa, $pd, $active, $key);
	}
	
	//战斗前清空各类标记
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att'));
		foreach ( $ex_attack_list as $key ) $pd['ex_dmg_'.$key.'_defend_success'] = 0;
		$chprocess($pa, $pd, $active);
	}	
}

?>
