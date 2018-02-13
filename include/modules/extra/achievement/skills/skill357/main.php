<?php

namespace skill357
{
	//各级要完成的成就名，如果不存在则取低的
	$ach357_name = array(
		1=>'发狂弹幕',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach357_desc= array(
		1=>'合成模式『EX』<:threshold:>次',
	);
	
	$ach357_proc_words = '目前进度';
	
	$ach357_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach357_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach357_qiegao_prize = array(
		1 => 3600,
	);
	
	function init() 
	{
		define('MOD_SKILL357_INFO','achievement;');
		define('MOD_SKILL357_ACHIEVEMENT_ID','57');
	}
	
	function acquire357(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(357,'cnt','0',$pa);
	}
	
	function lost357(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if ($itm0=='模式『EX』' && \skillbase\skill_query(357)){
			$cnt = (int)\skillbase\skill_getvalue(357,'cnt');
			\skillbase\skill_setvalue(357,'cnt',$cnt + 1);
		}
		$chprocess();	
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 357){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>