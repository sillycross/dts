<?php

namespace skill358
{
	//各级要完成的成就名，如果不存在则取低的
	$ach358_name = array(
		1=>'方块收集游戏',
		2=>'世代更替',
		3=>'宝石的无尽噩梦',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach358_desc= array(
		1=>'合成方块系道具<:threshold:>次',
	);
	
	$ach358_proc_words = '目前进度';
	
	$ach358_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach358_threshold = array(
		1 => 5,
		2 => 20,
		3 => 100,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach358_qiegao_prize = array(
		1 => 150,
		2 => 500,
		3 => 1800,
	);
	
	//各级给的切糕奖励
	$ach358_card_prize = array(
		1 => 163,
		3 => 160,
	);
	
	function init() 
	{
		define('MOD_SKILL358_INFO','achievement;');
		define('MOD_SKILL358_ACHIEVEMENT_ID','58');
	}
	
	function acquire358(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(358,'cnt','0',$pa);
	}
	
	function lost358(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ('cube' == $uip['mixcls'] && \skillbase\skill_query(358)){
			$cnt = (int)\skillbase\skill_getvalue(358,'cnt');
			\skillbase\skill_setvalue(358,'cnt',$cnt + 1);
		}
		$chprocess();	
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 358){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>