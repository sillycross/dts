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
		$itemspkinfo['U'] = '防火';
		$itemspkinfo['I'] = '防冻';
		$itemspkinfo['E'] = '绝缘';
		$itemspkinfo['W'] = '隔音';
		$itemspkinfo['D'] = '防爆';
		$itemspkinfo['a'] = '属性防御';
	}
	
	function get_ex_dmg_def_proc_rate(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 90;
	}
	
	function check_ex_single_dmg_def_attr(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att','ex_dmg_def','logger'));
		$r = 1;
		if (isset($def_kind[$key])) 
		{
			$ex_def_array = \attrbase\get_ex_def_array($pa, $pd, $active);
			if (in_array($def_kind[$key], $ex_def_array) || in_array('a', $ex_def_array))
			{
				$proc_rate = get_ex_dmg_def_proc_rate($pa, $pd, $active, $key);
				$dice = rand(0,99);
				if ($dice<$proc_rate)
				{
					$log .= "{$exdmgname[$key]}被防具防御了！";
					$r = 0.5;
					$pd['ex_dmg_'.$key.'_defend_success'] = 1;
				}
				else
				{
					$log .= "属性防御装备没能发挥应有的作用！";
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
		return $chprocess($pa, $pd, $active, $key);
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
