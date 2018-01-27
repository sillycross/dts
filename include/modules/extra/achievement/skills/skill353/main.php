<?php

namespace skill353
{
	//各级要完成的成就名，如果不存在则取低的
	$ach353_name = array(
		1=>'翻盘神器',
		2=>'黑项链',
		3=>'逆转之岚',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach353_desc= array(
		1=>'合成★一发逆转神话★ <:threshold:>次',
	);
	
	$ach353_proc_words = '目前进度';
	
	$ach353_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach353_threshold = array(
		1 => 1,
		2 => 5,
		3 => 12,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach353_qiegao_prize = array(
		1 => 444,
		2 => 2333,
		3 => 6666,
	);
	
	function init() 
	{
		define('MOD_SKILL353_INFO','achievement;');
		define('MOD_SKILL353_ACHIEVEMENT_ID','53');
	}
	
	function acquire353(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(353,'cnt','0',$pa);
	}
	
	function lost353(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if ($itm0=='★一发逆转神话★' && \skillbase\skill_query(353)){
			$cnt = (int)\skillbase\skill_getvalue(353,'cnt');
			\skillbase\skill_setvalue(353,'cnt',$cnt + 1);
		}
		$chprocess();	
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 353){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>