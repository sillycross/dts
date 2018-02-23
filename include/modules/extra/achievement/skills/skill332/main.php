<?php

namespace skill332
{
	//各级要完成的成就名，如果不存在则取低的
	$ach332_name = array(
		1=>'铁棒横扫',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach332_desc= array(
		1=>'殴系熟练度达到<:threshold:>点',
	);
	
	$ach332_proc_words = '最高熟练';
	
	$ach332_unit = '点';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach332_threshold = array(
		1 => 300,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach332_qiegao_prize = array(
		1 => 100,
	);
	
	//各级给的卡片奖励
	$ach332_card_prize = array(
		1 => 51,
	);
	
	function init() 
	{
		define('MOD_SKILL332_INFO','achievement;daily;');
		define('MOD_SKILL332_ACHIEVEMENT_ID','32');
		define('DAILY_TYPE332',1);
	}
	
	function acquire332(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(332,'cnt',0,$pa);
	}
	
	function lost332(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function act(){//每次行动后判断并记录最大殴熟
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$chprocess();
		
		if(\skillbase\skill_query(332) && $wp > \skillbase\skill_getvalue(332,'cnt')){
			\skillbase\skill_setvalue(332,'cnt',$wp);
		}
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 332){
			
			$var=max($pa['wp'], (int)\skillbase\skill_getvalue($achid,'cnt',$pa));
			$ret = max($ret, $var);
		}
		return $ret;
	}
}

?>