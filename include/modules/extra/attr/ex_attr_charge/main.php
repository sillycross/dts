<?php

namespace ex_attr_charge
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['N'] = '冲击';
		$itemspkdesc['N']='计算物理伤害时，对方装备防御力-50%';
		$itemspkremark['N']='40%概率生效';
	}
	
	//冲击触发率
	function get_ex_charge_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 40;
	}
	
	function check_charge_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$ex_att_array = \attrbase\get_ex_attack_array($pa, $pd, $active);
		if (\attrbase\check_in_itmsk('N', $ex_att_array))
		{
			$proc_rate = get_ex_charge_proc_rate($pa, $pd, $active);
			$dice = rand(0,99);
			if ($dice<$proc_rate)
			{
				$pa['physical_charge_success'] = 1;
				return 1;
			}
		}
		return 0;
	}
	
	//冲击触发导致防具防御减半
	function get_external_def_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (check_charge_proc($pa, $pd, $active))
		{
			eval(import_module('logger'));
			if ($active)
				$log .= "<span class=\"yellow b\">你的攻击隔着{$pd['name']}的防具造成了伤害！</span><br>";
			else  $log .= "<span class=\"yellow b\">{$pa['name']}的攻击隔着你的防具造成了伤害！</span><br>";
			return $chprocess($pa, $pd, $active)/2;
		}
		else  return $chprocess($pa, $pd, $active);
	}
	
	//战前清空标记
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['physical_charge_success'] = 0;
		$chprocess($pa, $pd, $active);
	}
}

?>
