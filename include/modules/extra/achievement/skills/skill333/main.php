<?php

namespace skill333
{
	//各级要完成的成就名，如果不存在则取低的
	$ach333_name = array(
		1=>'快刀乱麻',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach333_desc= array(
		1=>'斩系熟练度达到<:threshold:>点',
	);
	
	$ach333_proc_words = '最高熟练';
	
	$ach333_unit = '点';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach333_threshold = array(
		1 => 300,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach333_qiegao_prize = array(
		1 => 100,
	);
	
	//各级给的卡片奖励
	$ach333_card_prize = array(
		1 => 52,
	);
	
	function init() 
	{
		define('MOD_SKILL333_INFO','achievement;daily;');
		define('MOD_SKILL333_ACHIEVEMENT_ID','33');
		define('DAILY_TYPE333',1);
	}
	
	function acquire333(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(333,'cnt',0,$pa);
	}
	
	function lost333(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function act(){//每次行动后判断并记录最大殴熟
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$chprocess();
		
		if(\skillbase\skill_query(333) && $wk > \skillbase\skill_getvalue(333,'cnt')){
			\skillbase\skill_setvalue(333,'cnt',$wk);
		}
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 333){
			
			$var=max($pa['wk'], (int)\skillbase\skill_getvalue($achid,'cnt',$pa));
			$ret = max($ret, $var);
		}
		return $ret;
	}
}

?>