<?php

namespace skill303
{
	//各级要完成的成就名，如果不存在则取低的
	$ach303_name = array(
		1=>'篝火的引导',
		2=>'世界的树形图',
		3=>'地=月',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach303_desc= array(
		1=>'合成【KEY系燃烧弹】<:threshold:>次',
	);
	
	$ach303_proc_words = '目前进度';
	
	$ach303_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach303_threshold = array(
		1 => 1,
		2 => 5,
		3 => 15,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach303_qiegao_prize = array(
		1 => 300,
		2 => 1200,
		3 => 2700,
	);
	
	function init() 
	{
		define('MOD_SKILL303_INFO','achievement;');
		define('MOD_SKILL303_ACHIEVEMENT_ID','3');
	}
	
	function acquire303(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(303,'cnt','0',$pa);
	}
	
	function lost303(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if ($itm0=='【KEY系燃烧弹】' && \skillbase\skill_query(303)){
			$cnt = (int)\skillbase\skill_getvalue(303,'cnt');
			\skillbase\skill_setvalue(303,'cnt',$cnt + 1);
		}
		$chprocess();	
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 303){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>