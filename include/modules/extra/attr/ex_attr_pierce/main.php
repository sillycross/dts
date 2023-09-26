<?php

namespace ex_attr_pierce
{
	$phy_pierce_rate = 30;
	$attr_pierce_rate = 30;
	
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['n'] = '物穿';
		$itemspkdesc['n']='计算物理伤害时，可能无视对方装备的物理防御属性';
		$itemspkremark['n']='无视列表：防殴、防斩、防弹、防投、防爆、防灵、防连、物防和物抹；<br>30%概率生效；与“属穿”同时生效时能无视“控伤”属性';
		
		$itemspkinfo['y'] = '属穿';
		$itemspkdesc['y']='计算属性伤害时，可能无视对方装备的属性防御属性';
		$itemspkremark['y']='无视列表：防火、防冻、防毒、隔音、绝缘、属防和属抹；<br>30%概率生效；与“物穿”同时生效时能无视“控伤”属性';
	}
	
	//贯穿触发率
	function get_ex_pierce_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_attr_pierce'));
		return $phy_pierce_rate;
	}
	
	//属穿触发率
	function get_attr_pierce_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_attr_pierce'));
		return $attr_pierce_rate;
	}
	
	function check_phy_pierce_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$ex_att_array = \attrbase\get_ex_attack_array($pa, $pd, $active);
		if (\attrbase\check_in_itmsk('n', $ex_att_array))
		{
			$proc_rate = get_ex_pierce_proc_rate($pa, $pd, $active);
			$dice = rand(0,99);
			if ($dice<$proc_rate)
			{
				$log .= \battle\battlelog_parser($pa, $pd, $active, "<span class=\"yellow b\"><:pa_name:>的攻击贯穿了<:pd_name:>的物理防御属性！</span><br>");
				$pa['physical_pierce_success'] = 1;
			}
		}
		return;
	}
	
	function check_attr_pierce_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$ex_att_array = \attrbase\get_ex_attack_array($pa, $pd, $active);
		if (\attrbase\check_in_itmsk('y', $ex_att_array))
		{
			$proc_rate = get_attr_pierce_proc_rate($pa, $pd, $active);
			$dice = rand(0,99);
			if ($dice<$proc_rate)
			{
				$log .= \battle\battlelog_parser($pa, $pd, $active, "<span class=\"yellow b\"><:pa_name:>的攻击穿透了<:pd_name:>的属性防御属性！</span><br>");
				$pa['attr_pierce_success'] = 1;
			}
		}
		return;
	}
	
	function check_ex_rapid_def_exists(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret =  $chprocess($pa, $pd, $active);
		//贯穿触发后跳过防连判定
		if ($pa['physical_pierce_success']) $ret = 0;
		return $ret;
	}
	
	function check_physical_def_attr(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//贯穿触发后跳过减半属性判定
		if ($pa['physical_pierce_success']) return Array();
		return $chprocess($pa, $pd, $active);
	}
	
	function check_physical_nullify(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//贯穿触发后跳过伤抹属性判定
		if ($pa['physical_pierce_success']) return array();
		return $chprocess($pa, $pd, $active);
	}
	
	function check_ex_single_dmg_def_attr(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//属穿触发后跳过减半属性判定
		if ($pa['attr_pierce_success']) return 1;
		return $chprocess($pa, $pd, $active,$key);
	}
	
	function check_ex_dmg_nullify(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//属穿触发后跳过属抹属性判定
		if ($pa['attr_pierce_success']) return 0;
		return $chprocess($pa, $pd, $active);
	}
	
	//物穿的判定是在计算物理伤害之前
	function calculate_physical_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		check_phy_pierce_proc($pa, $pd, $active);
		return $chprocess($pa, $pd, $active);
	}
	
	//属穿的判定是在计算属性伤害之前
	function calculate_ex_attack_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($pa['is_hit']) check_attr_pierce_proc($pa, $pd, $active);
		return $chprocess($pa, $pd, $active);
	}
	
	//战前清空标记
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['physical_pierce_success'] = $pa['attr_pierce_success'] = 0;
		$chprocess($pa, $pd, $active);
	}
	
	//双穿同时生效时控血失效
	function check_dmg_def_attr(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!empty($pa['physical_pierce_success']) && !empty($pa['attr_pierce_success']) && \attrbase\check_in_itmsk('h',\attrbase\get_ex_def_array($pa, $pd, $active)))
		{
			eval(import_module('logger'));
			$log .= \battle\battlelog_parser($pa, $pd, $active, '<span class="red b"><:pa_name:>的攻击贯穿了<:pd_name:>的控血属性！</span><br>');
			return;
		}
		$chprocess($pa, $pd, $active);
	}
		
}

?>