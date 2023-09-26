<?php

namespace ex_rapid_def
{
	
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['R'] = '防连';
		$itemspkdesc['R']='被连击武器攻击时，第2次及之后的攻击伤害减半。';
		$itemspkremark['R']='10%概率失效';
	}
	
	function get_ex_rapid_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 90;
	}
	
	function check_ex_rapid_def_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$proc_rate = get_ex_rapid_def_proc_rate($pa, $pd, $active);
		$dice = rand(0,99);
		return $dice < $proc_rate;
	}
	
	function check_ex_rapid_def_exists(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$defarr=\attrbase\get_ex_def_array($pa, $pd, $active);
		return \attrbase\check_in_itmsk('R',$defarr);
	}
	
	function check_ex_rapid_def(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$ret = 0;
		if (check_ex_rapid_def_exists($pa, $pd, $active)) {
			if(check_ex_rapid_def_proc($pa, $pd, $active)){
				$log .= \battle\battlelog_parser($pa, $pd, $active, '<span class="yellow b">然而<:pd_name:>的防具削弱了<:pa_name:>的后几次连续攻击！</span><br>');
				$ret = 1;
			}else{
				$log .= \battle\battlelog_parser($pa, $pd, $active, '<span class="red b"><:pd_name:>的防具没能削弱<:pa_name:>的连续攻击！</span><br>');
			}
		}
		return $ret;
	}
	
	function get_rapid_damage_modifier(&$pa, &$pd, $active, $hit_time)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//连击伤害修正
		$ret = $chprocess($pa, $pd, $active, $hit_time);
		if($ret > 1 && check_ex_rapid_def($pa, $pd, $active)){//只有命中2次以上才会触发防连
			$ret = 1 + ($ret - 1) / 2; //防连效果是第1次以后的攻击加成全部减半
		}
		//$z = Array( 0 => 0, 1 => 1, 2 => 2, 3 => 2.8, 4 => 3.4, 5 => 4, 6 => 4.6 );
		return $ret;
	}
}

?>