<?php

namespace ex_dmg_nullify
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['b'] = '属抹';
		$itemspkdesc['b']='抹消受到的属性伤害至1，并且免疫异常状态';
		$itemspkremark['b']='4%概率失效';
	}
	
	function get_ex_dmg_nullify_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 96;
	}
	
	function check_ex_dmg_nullify(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att','logger'));
		$ex_att_array = \attrbase\get_ex_attack_array($pa, $pd, $active);
		$ex_def_array = \attrbase\get_ex_def_array($pa, $pd, $active);
		//有属性攻击才进入判断
		$flag = 0; $exnum = 0;
		foreach ($ex_attack_list as $key) if (in_array($key,$ex_att_array)) { $flag = 1; $exnum++; }
		if ($flag && in_array('b', $ex_def_array))
		{
			$proc_rate = get_ex_dmg_nullify_proc_rate($pa, $pd, $active);
			$dice = rand(0,99);
			if ($dice<$proc_rate)
			{
				$log .= "<span class=\"yellow b\">属性攻击的力量完全被防具吸收了！</span>只造成了<span class=\"red b\">{$exnum}</span>点伤害！<br>";
				$pa['ex_dmg_dealt'] = $exnum;
				$pd['exdmg_nullify_success'] = 1;
				return 1;
			}
			else
			{
				$log .= '<span class="red b">防具免疫属性攻击的效果竟然失效了！</span><br>';
				return 0;
			}
		}
		return 0;
	}
	
	//是否执行属性伤害
	function check_ex_attack_available(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if ($pa['is_hit'] && check_ex_dmg_nullify($pa, $pd, $active)) {
			$ret = false;
			$pa['dmg_dealt'] += $pa['ex_dmg_dealt'];
			$pa['mult_words_fdmgbs'] = \attack\add_format($pa['ex_dmg_dealt'], $pa['mult_words_fdmgbs']);
		}
		return $ret;
	}
	
//	function calculate_ex_attack_dmg(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if (check_ex_dmg_nullify($pa, $pd, $active))
//			return $pa['ex_dmg_dealt'];
//		else  return $chprocess($pa, $pd, $active);
//	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pd['exdmg_nullify_success'] = 0;
		$chprocess($pa, $pd, $active);
	}	
}

?>