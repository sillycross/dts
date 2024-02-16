<?php

namespace skill271
{
	$skill271deno = 300;
	
	function init() 
	{
		define('MOD_SKILL271_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[271] = '神裁';
	}
	
	function acquire271(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost271(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked271(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=13;
	}
	
	function get_skill271_times(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger','skill271'));
		
		$o_log = $log;//某些技能（比如天威）会在获取熟练度时写log，要屏蔽掉这种消息
		$s1 = \weapon\get_skill($pa, $pd, $active);
		$s2 = \weapon\get_skill($pd, $pa, 1-$active);
		$log = $o_log;
		//测试用语句
		//$log .= '你的熟练度'.$s1.' 敌人熟练度'.$s2.'<br>';
		
		$t = 0;
		if ($s1 > $s2) {
			$t = floor(($s1-$s2)/$skill271deno);
		}
		//$t = floor($s1/$skill271deno);
		return $t;
	}
	
	function get_all_attr271()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_phy_def'));
		$arr = $def_kind;
		eval(import_module('ex_dmg_def'));
		$arr = array_merge($arr,$def_kind);
		$arr['r'] = 'R';
		return $arr;
	}
	
	function get_avaliable_attr271(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$all_arr = get_all_attr271();
		$a_arr = \attrbase\get_ex_attack_array($pa, $pd, $active);
		$a_arr = array_diff($a_arr,array('P','K','G','C','D','F'));
		$a_arr[] = $pa['wep_kind'];
		$ad_arr = array();
		foreach($a_arr as $ak){
			if(isset($all_arr[$ak]))	$ad_arr[] = $all_arr[$ak];
		}
		//var_dump($ad_arr);
		if(!$ad_arr) return array();
		$arr = \attrbase\get_ex_def_array($pa, $pd, $active);		
		if(\attrbase\check_in_itmsk('A', $arr)) {
			eval(import_module('ex_phy_def'));
			$arr = array_diff(array_merge($arr, array_values($def_kind)), array('A'));
		}
		if(\attrbase\check_in_itmsk('a', $arr)) {
			eval(import_module('ex_dmg_def'));
			$arr = array_diff(array_merge($arr, array_values($def_kind)), array('a'));
		}
		
		$arr = array_unique(array_intersect($arr, $ad_arr));
		return $arr;
	}
	
	//每次攻击前重置一下随机无视的属性
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(271, $pa) && check_unlocked271($pa)){
			$t = get_skill271_times($pa, $pd, $active);
			if($t > 0){
				$pd_def_arr = get_avaliable_attr271($pa, $pd, $active);
				//var_dump($pd_def_arr);
				if($pd_def_arr){
					shuffle($pd_def_arr);
					$pa['skill271_list'] = array_slice($pd_def_arr, 0, $t);
					eval(import_module('logger','itemmain'));
					$words = array();
					foreach($pa['skill271_list'] as $val){
						$words[] = $itemspkinfo[$val];
					}
					$words = implode('、',$words);
					$log .= \battle\battlelog_parser($pa, $pd, $active, '<:pa_name:>的「神裁」技能使<:pa_name:>无视了<:pd_name:>的'.$words.'属性！<br>');
				}
			}
			
		}
	}
	
//	function get_ex_def_array_core(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$ret = $chprocess($pa, $pd, $active);
//		if (!empty($pa['skill271_list'])) {
//			$ret = array_diff($ret, $pa['skill271_list']);
//		}
//		return $ret;
//	}

	//物理防御抵消
	function check_ex_phy_def_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		eval(import_module('ex_phy_def'));
		if(!empty($pa['skill271_list']) && in_array($def_kind[$pa['wep_kind']], $pa['skill271_list'])){
			$ret = 0;
			//$log .= \battle\battlelog_parser($pa, $pd, $active, '<:pa_name:>的「神裁」技能生效了！<br>');
		}
		return $ret;
	}
	
	//属性防御抵消
	function check_ex_dmg_def_proc(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $key);
		eval(import_module('ex_dmg_def'));
		if(!empty($pa['skill271_list']) && in_array($def_kind[$key], $pa['skill271_list'])){
			$ret = 0;
		}
		return $ret;
	}
	
	//防连抵消
	function check_ex_rapid_def_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(!empty($pa['skill271_list']) && in_array('R', $pa['skill271_list'])){
			$ret = 0;
		}
		return $ret;
	}
}

?>