<?php

namespace skill381
{
	//各级要完成的成就名，如果不存在则取低的
	$ach381_name = array(
		1=>'听我唱歌吧',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach381_desc= array(
		1=>'最大歌魂达到<:threshold:>点',
	);
	
	$ach381_proc_words = '最高达到';
	
	$ach381_unit = '点';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach381_threshold = array(
		1 => 233,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach381_qiegao_prize = array(
		1 => 150,
	);
	
	//各级给的卡片奖励
	$ach381_card_prize = array(
		//1 => 22,
	);
	
	function init() 
	{
		define('MOD_SKILL381_INFO','achievement;daily;');
		define('MOD_SKILL381_ACHIEVEMENT_ID','81');
		define('DAILY_TYPE381',1);
	}
	
	function acquire381(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(381,'cnt',0,$pa);
	}
	
	function lost381(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function act(){//每次行动后判断并记录最大歌魂
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$chprocess();
		
		if(\skillbase\skill_query(381) && $mss > \skillbase\skill_getvalue(381,'cnt')){
			\skillbase\skill_setvalue(381,'cnt',$mss);
		}
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 381){
			
			$var=max($pa['mss'], (int)\skillbase\skill_getvalue($achid,'cnt',$pa));
			$ret = max($ret, $var);
		}
		return $ret;
	}
}

?>