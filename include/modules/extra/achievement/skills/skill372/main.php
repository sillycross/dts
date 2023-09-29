<?php

namespace skill372
{
	//各级要完成的成就名，如果不存在则取低的
	$ach372_name = array(
		1=>'原始积累',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach372_desc= array(
		1=>'持有金钱数达到<:threshold:>元',
	);
	
	$ach372_proc_words = '最高持有';
	
	$ach372_unit = '元';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach372_threshold = array(
		1 => 1800,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach372_qiegao_prize = array(
		1 => 180,
	);
	
	//各级给的卡片奖励
	$ach372_card_prize = array(
		1 => 22,
	);
	
	function init() 
	{
		define('MOD_SKILL372_INFO','achievement;daily;');
		define('MOD_SKILL372_ACHIEVEMENT_ID','72');
		define('DAILY_TYPE372',3);
	}
	
	function acquire372(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(372,'cnt',0,$pa);
	}
	
	function lost372(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function act(){//每次行动后判断并记录最大金钱
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$chprocess();
		
		if(\skillbase\skill_query(372) && $money > \skillbase\skill_getvalue(372,'cnt')){
			\skillbase\skill_setvalue(372,'cnt',$money);
		}
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 372){
			
			$var=max($pa['money'], (int)\skillbase\skill_getvalue($achid,'cnt',$pa));
			$ret = max($ret, $var);
		}
		return $ret;
	}
}

?>