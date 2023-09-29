<?php

namespace skill373
{
	//各级要完成的成就名，如果不存在则取低的
	$ach373_name = array(
		1=>'小制作',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach373_desc= array(
		1=>'合成道具<:threshold:>次',
	);
	
	$ach373_proc_words = '当前进度';
	
	$ach373_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach373_threshold = array(
		1 => 5,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach373_qiegao_prize = array(
		1 => 200,
	);
	
	//各级给的卡片奖励
//	$ach373_card_prize = array(
//		1 => 22,
//	);
	
	function init() 
	{
		define('MOD_SKILL373_INFO','achievement;daily;');
		define('MOD_SKILL373_ACHIEVEMENT_ID','73');
		define('DAILY_TYPE373',3);
	}
	
	function acquire373(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(373,'cnt',0,$pa);
	}
	
	function lost373(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 373){
			$ret += (int)\skillbase\skill_getvalue(373,'cnt',$pa);
		}
		return $ret;
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$chprocess();
		if(\skillbase\skill_query(373)){
			$cnt = (int)\skillbase\skill_getvalue(373,'cnt') + 1;
			\skillbase\skill_setvalue(373,'cnt',$cnt);
		}
	}
}

?>