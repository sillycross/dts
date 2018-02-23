<?php

namespace skill338
{
	//各级要完成的成就名，如果不存在则取低的
	$ach338_name = array(
		1=>'真男人，结构抗',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach338_desc= array(
		1=>'将最大生命提升到<:threshold:>点以上',
	);
	
	$ach338_proc_words = '最高纪录';
	
	$ach338_unit = '点';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach338_threshold = array(
		1 => 800,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach338_qiegao_prize = array(
		1 => 200,
	);
	
	function init() 
	{
		define('MOD_SKILL338_INFO','achievement;daily;');
		define('MOD_SKILL338_ACHIEVEMENT_ID','38');
		define('DAILY_TYPE338',1);
	}
	
	function acquire338(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(338,'cnt',0,$pa);
	}
	
	function lost338(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function act(){//每次行动后判断并记录最大殴熟
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$chprocess();
		
		if(\skillbase\skill_query(338) && $mhp > \skillbase\skill_getvalue(338,'cnt')){
			\skillbase\skill_setvalue(338,'cnt',$mhp);
		}
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 338){
			
			$var=max($pa['mhp'], (int)\skillbase\skill_getvalue($achid,'cnt',$pa));
			$ret = max($ret, $var);
		}
		return $ret;
	}
}

?>