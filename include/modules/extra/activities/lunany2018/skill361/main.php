<?php

namespace skill361
{
	//各级要完成的成就名，如果不存在则取低的
	$ach361_name = array(
		1=>'不忘初心 LV1',
		2=>'不忘初心 LV2',
		3=>'不忘初心 LV3',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach361_desc= array(
		1=>'在<span class="evergreen">标准模式</span>获胜<:threshold:>场',
	);
	
	$ach361_proc_words = '目前进度';
	
	$ach361_unit = '场';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach361_threshold = array(
		1 => 1,
		2 => 3,
		3 => 6,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach361_qiegao_prize = array(
		1 => 100,
		2 => 400,
		3 => 900,
	);
	
	//各级给的卡片奖励
	$ach361_card_prize = array(
		//1 => 208,
	);
	
	function init() 
	{
		define('MOD_SKILL361_INFO','achievement;spec-activity;');
		define('MOD_SKILL361_ACHIEVEMENT_ID','61');
	}
	
	function acquire361(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost361(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 361){
			eval(import_module('sys'));
			if($winner === $pa['name'] && 0==$gametype) {
				$ret += 1;
			}
		}
		return $ret;
	}
}

?>