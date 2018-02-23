<?php

namespace skill360
{
	//各级要完成的成就名，如果不存在则取低的
	$ach360_name = array(
		1=>'大吉大利 LV1',
		2=>'大吉大利 LV2',
		3=>'大吉大利 LV3',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach360_desc= array(
		1=>'游戏结束时身上的金钱尾数为888，合计达成<:threshold:>场',
	);
	
	$ach360_proc_words = '目前场次';
	
	$ach360_unit = '场';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach360_threshold = array(
		1 => 1,
		2 => 3,
		3 => 6,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach360_qiegao_prize = array(
		1 => 188,
		2 => 588,
		3 => 988,
	);
	
	//各级给的卡片奖励
	$ach360_card_prize = array(
		3 => 164,
	);
	
	function init() 
	{
		define('MOD_SKILL360_INFO','achievement;spec-activity;');
		define('MOD_SKILL360_ACHIEVEMENT_ID','60');
	}
	
	function acquire360(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(360,'cnt','0',$pa);
	}
	
	function lost360(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 360){
			$var = $pa['money'];
			if($var < 888) return $ret;
			if(substr($var,strlen($var)-3) == '888') $ret += 1;
		}
		return $ret;
	}
	
	function get_lny2018_icon($achid, $c, $top_flag)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if(!$c) {
			$ach_iconid = 'lny2018_N.png';
		}elseif(!$top_flag) {
			$ach_iconid = 'lny2018_D.png';
		}else {
			$ach_iconid = 'lny2018_DA.png';
		}

		return $ach_iconid;
	}
	
	function show_achievement_icon($achid, $c, $top_flag)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($achid, $c, $top_flag);
		if(360 == $achid) {
			$ret = get_lny2018_icon($achid, $c, $top_flag);
		}
		return $ret;
	}
}

?>