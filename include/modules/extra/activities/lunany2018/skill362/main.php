<?php

namespace skill362
{
	//各级要完成的成就名，如果不存在则取低的
	$ach362_name = array(
		1=>'红包拿来 LV1',
		2=>'红包拿来 LV2',
		3=>'红包拿来 LV3',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach362_desc= array(
		1=>'击杀NPC获得的切糕数目达到<:threshold:>',
	);
	
	$ach362_proc_words = '目前进度';
	
	$ach362_unit = '切糕';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach362_threshold = array(
		1 => 100,
		2 => 800,
		3 => 6400,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach362_qiegao_prize = array(
		1 => 66,
		2 => 666,
		3 => 6666,
	);
	
	//各级给的卡片奖励
	$ach362_card_prize = array(
		3 => array(200, 201, 202, 203, 204),
	);
	
	function init() 
	{
		define('MOD_SKILL362_INFO','achievement;spec-activity;');
		define('MOD_SKILL362_ACHIEVEMENT_ID','62');
	}
	
	function acquire362(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(362,'cnt','0',$pa);
	}
	
	function lost362(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 362){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
	
	function show_achievement_icon($achid, $c, $top_flag)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($achid, $c, $top_flag);
		if(362 == $achid) {
			$ret = \skill360\get_lny2018_icon($achid, $c, $top_flag);
		}
		return $ret;
	}
	
	function battle_get_qiegao(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if(\skillbase\skill_query(362,$pa)) {
			$cnt353 = (int)\skillbase\skill_getvalue(362,'cnt',$pa);
			\skillbase\skill_setvalue(362,'cnt',$cnt353+$ret,$pa);
		}
		return $ret;
	}
}

?>