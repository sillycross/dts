<?php

namespace skill509
{
	$skill509_factor = 4;
	
	function init() 
	{
		define('MOD_SKILL509_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[509] = '奇袭';
	}
	
	function acquire509(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost509(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//如果斩杀技能存在，只有在斩杀触发时会生效
	function check_unlocked509(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$flag = 1;
		if(\skillbase\skill_query(507,$pa) && !\skill507\check_unlocked507($pa)) $flag = 0;
		return $flag;
	}
	
	function get_var509(&$ldata, &$edata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = 0;
		if(\skillbase\skill_query(509,$edata) && check_unlocked509($edata)){
			eval(import_module('skill509','weapon'));
			$l_att_method = \weapon\get_attack_method($ldata);
			$range_509 = $rangeinfo[$l_att_method];
			if(empty($range_509)) $range_509 = 9;//爆系射程算比重枪还远，因为在加算阶段，所以实际非常伤
			//削弱，从投系射程开始才有加值。现在最高的爆系的效果是20%，重枪是16%，普通枪械12%，投系是4%
			$range_509 = max(0, $range_509 - 4);
			$ret = $range_509 * $skill509_factor;
		}
		return $ret;
	}
	
	//实际上是使主动探索者的先制率下降
	function calculate_active_obbs(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$var_509 = get_var509($ldata, $edata);
		if($var_509) $ldata['active_words'] = \attack\add_format(-$var_509, $ldata['active_words'],0);
		return $chprocess($ldata,$edata)-$var_509;
	}
}

?>