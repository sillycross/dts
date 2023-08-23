<?php

namespace skill359
{
	//各级要完成的成就名，如果不存在则取低的
	$ach359_name = array(
		1=>'杆位',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach359_desc= array(
		1=>'在<span class="red b">极速模式</span>开局15分钟之内达成锁定解除结局',
	);
	
	$ach359_proc_words = '最快速度';
	
	$ach359_unit = '秒';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach359_threshold = array(
		1 => 900,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach359_qiegao_prize = array(
		1 => 666,
	);
	
	//各级给的卡片奖励
	$ach359_card_prize = array(
		1 => 208,
	);
	
	function init() 
	{
		define('MOD_SKILL359_INFO','achievement;');
		define('MOD_SKILL359_ACHIEVEMENT_ID','59');
	}
	
	function acquire359(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost359(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 359){
			eval(import_module('sys'));
			if(\sys\is_winner($pa['name'],$winner) && 19==$gametype && 3 == $winmode) {
				$time359 = $pa['endtime']-$gamevars['o_starttime'];
				if($ret <= 0) $ret = $time359;
				else $ret = min($ret, $time359);
			}
		}
		return $ret;
	}
	
	//判定数据与阈值的关系，这里是小于阈值算达成
	function ach_finalize_check_progress(&$pa, $t, $data, $achid){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(359 == $achid) {
			if($data <= 0) return false;
			else return $data <= $t;
		}
		else return $chprocess($pa, $t, $data, $achid);
	}
}

?>