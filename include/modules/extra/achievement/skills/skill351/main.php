<?php

namespace skill351
{
	//各级要完成的成就名，如果不存在则取低的
	$ach351_name = array(
		1=>'免费测试员',
		2=>'办公室哲学',
		4=>'是男人就下一百层',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach351_desc= array(
		0=>'在<span class="clan">除错模式</span>中的除错进度达到第<:threshold:>层',
	);
	
	$ach351_proc_words = '最高进度';
	
	$ach351_unit = '层';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach351_threshold = array(
		1 => 30,
		2 => 60,
		3 => 100,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach351_qiegao_prize = array(
		1 => 500,
		2 => 1500,
		3 => 3000,
	);
	
	//各级给的卡片奖励
	$ach351_card_prize = array(
		1 => 94,
		2 => 117,
	);
	
	function init() 
	{
		define('MOD_SKILL351_INFO','achievement;');
		define('MOD_SKILL351_ACHIEVEMENT_ID','51');
	}
	
	function acquire351(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(351,'cnt',0,$pa);
	}
	
	function lost351(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function gtype1_post_rank_event(&$pa, $cl, $rk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(351,'cnt',$cl,$pa);
		$chprocess($pa, $cl, $rk);
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		eval(import_module('sys'));
		if($achid == 351 && 1==$gametype){
			$var=(int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret = max($ret, $var);
		}
		return $ret;
	}
}

?>