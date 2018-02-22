<?php

namespace skill335
{
	//各级要完成的成就名，如果不存在则取低的
	$ach335_name = array(
		1=>'四分球',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach335_desc= array(
		1=>'投系熟练度达到<:threshold:>点',
	);
	
	$ach335_proc_words = '最高熟练';
	
	$ach335_unit = '点';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach335_threshold = array(
		1 => 300,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach335_qiegao_prize = array(
		1 => 100,
	);
	
	//各级给的卡片奖励
	$ach335_card_prize = array(
		1 => 53,
	);
	
	function init() 
	{
		define('MOD_SKILL335_INFO','achievement;daily;');
		define('MOD_SKILL335_ACHIEVEMENT_ID','35');
		define('DAILY_TYPE335',1);
	}
	
	function acquire335(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(335,'cnt',0,$pa);
	}
	
	function lost335(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function act(){//每次行动后判断并记录最大殴熟
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$chprocess();
		
		if(\skillbase\skill_query(335) && $wc > \skillbase\skill_getvalue(335,'cnt')){
			\skillbase\skill_setvalue(335,'cnt',$wc);
		}
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 335){
			
			$var=max($pa['wc'], (int)\skillbase\skill_getvalue($achid,'cnt',$pa));
			$ret = max($ret, $var);
		}
		return $ret;
	}
}

?>