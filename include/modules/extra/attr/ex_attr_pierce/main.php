<?php

namespace ex_attr_pierce
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['n'] = '贯穿';
	}
	
	//贯穿触发率
	function get_ex_pierce_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 30;
	}
	
	//注意这个函数返回的必须是一个数组
	function check_pierce_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$ex_att_array = \attrbase\get_ex_attack_array($pa, $pd, $active);
		if (in_array('n', $ex_att_array))
		{
			$proc_rate = get_ex_pierce_proc_rate($pa, $pd, $active);
			$dice = rand(0,99);
			if ($dice<$proc_rate)
			{
				if ($active)
					$log .= "<span class=\"yellow\">你的攻击贯穿了{$pd['name']}的防具！</span><br>";
				else  $log .= "<span class=\"yellow\">{$pa['name']}的攻击贯穿了你的防具！</span><br>";
				$pa['physical_pierce_success'] = 1;
			}
		}
		return Array();
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
		if ($pa['physical_pierce_success']) return 0;
		return $chprocess($pa, $pd, $active);
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return array_merge(check_pierce_proc($pa, $pd, $active), $chprocess($pa, $pd, $active));
	}
	
	//战前清空标记
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['physical_pierce_success'] = 0;
		$chprocess($pa, $pd, $active);
	}
}

?>
