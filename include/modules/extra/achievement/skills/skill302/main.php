<?php

namespace skill302
{
	//各级要完成的成就名，如果不存在则取低的
	$ach302_name = array(
		1=>'永恒世界的住人',
		2=>'幻想世界的往人',
		3=>'永恒的覆唱',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach302_desc= array(
		1=>'合成【KEY系催泪弹】<:threshold:>次',
	);
	
	$ach302_proc_words = '目前进度';
	
	$ach302_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach302_threshold = array(
		1 => 1,
		2 => 5,
		3 => 15,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach302_qiegao_prize = array(
		1 => 200,
		2 => 800,
		3 => 1800,
	);
	
	//各级给的卡片奖励
	$ach302_card_prize = array(
		3 => 66,
	);
	
	function init() 
	{
		define('MOD_SKILL302_INFO','achievement;');
		define('MOD_SKILL302_ACHIEVEMENT_ID','2');
	}
	
	function acquire302(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(302,'cnt','0',$pa);
	}
	
	function lost302(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if ($itm0=='【KEY系催泪弹】' && \skillbase\skill_query(302)){
			$cnt = (int)\skillbase\skill_getvalue(302,'cnt');
			\skillbase\skill_setvalue(302,'cnt',$cnt + 1);
		}
		$chprocess();	
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 302){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>