<?php

namespace skill350
{
	//各级要完成的成就名，如果不存在则取低的
	$ach350_name = array(
		1=>'唯快不破',
		2=>'超越音速',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach350_desc= array(
		1=>'在<span class="red b">极速模式</span>中锁定解除<:threshold:>次',
	);
	
	$ach350_proc_words = '目前进度';
	
	$ach350_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach350_threshold = array(
		1 => 1,
		2 => 10,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach350_qiegao_prize = array(
		1 => 270,
		2 => 2000,
	);
	
	//各级给的卡片奖励
	$ach350_card_prize = array(
		2 => 205,
	);
	
	function init() 
	{
		define('MOD_SKILL350_INFO','achievement;');
		define('MOD_SKILL350_ACHIEVEMENT_ID','50');
	}
	
	function acquire350(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(350,'cnt',0,$pa);
	}
	
	function lost350(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 350){
			eval(import_module('sys'));
			if(\sys\is_winner($pa['name'],$winner) && 19==$gametype && 3==$winmode) $ret += 1;
		}
		return $ret;
	}
}

?>