<?php

namespace skill370
{
	//各级要完成的成就名，如果不存在则取低的
	$ach370_name = array(
		1=>'肉弹战车',
		2=>'走向妖怪之路',
		3=>'直到生命将恐惧消灭',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach370_desc= array(
		1=>'获胜时最大生命达到2000点或更高',
		2=>'获胜时最大生命达到4000点或更高',
		3=>'获胜时最大生命达到8000点或更高',
	);

	
	$ach370_proc_words = '最高纪录';
	
	$ach370_unit = '点';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach370_threshold = array(
		1 => 2000,
		2 => 4000,
		3 => 8000,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach370_qiegao_prize = array(
		1 => 300,
		2 => 400,
		3 => 500,
	);
	
	//各级给的卡片奖励
	$ach370_card_prize = array(
		3 => 186,
	);
	
	function init() 
	{
		define('MOD_SKILL370_INFO','achievement;');
		define('MOD_SKILL370_ACHIEVEMENT_ID','70');
	}
	
	function acquire370(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost370(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 370){
			eval(import_module('sys','skill370'));
			if($winner === $pa['name']) {
				if($pa['mhp'] > $ret) $ret = $pa['mhp'];
			}
		}
		return $ret;
	}
}

?>