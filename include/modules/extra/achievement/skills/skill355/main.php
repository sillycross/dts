<?php

namespace skill355
{
	//各级要完成的成就名，如果不存在则取低的
	$ach355_name = array(
		1=>'我最棒的朋友',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach355_desc= array(
		1=>'合成概念武装『破则』<:threshold:>次',
	);
	
	$ach355_proc_words = '目前进度';
	
	$ach355_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach355_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach355_qiegao_prize = array(
		1 => 999,
	);
	
	//各级给的卡片奖励
	$ach355_card_prize = array(
		1 => 207,
	);
	
	function init() 
	{
		define('MOD_SKILL355_INFO','achievement;');
		define('MOD_SKILL355_ACHIEVEMENT_ID','55');
	}
	
	function acquire355(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(355,'cnt','0',$pa);
	}
	
	function lost355(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if ($itm0=='概念武装『破则』' && \skillbase\skill_query(355)){
			$cnt = (int)\skillbase\skill_getvalue(355,'cnt');
			\skillbase\skill_setvalue(355,'cnt',$cnt + 1);
		}
		$chprocess();	
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 355){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>