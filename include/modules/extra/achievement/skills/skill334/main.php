<?php

namespace skill334
{
	//各级要完成的成就名，如果不存在则取低的
	$ach334_name = array(
		1=>'枪杆子',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach334_desc= array(
		1=>'射系熟练度达到<:threshold:>点',
	);
	
	$ach334_proc_words = '最高熟练';
	
	$ach334_unit = '点';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach334_threshold = array(
		1 => 300,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach334_qiegao_prize = array(
		1 => 100,
	);
	
	//各级给的卡片奖励
	$ach334_card_prize = array(
		1 => 54,
	);
	
	function init() 
	{
		define('MOD_SKILL334_INFO','achievement;daily;');
		define('MOD_SKILL334_ACHIEVEMENT_ID','34');
		define('DAILY_TYPE334',1);
	}
	
	function acquire334(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(334,'cnt',0,$pa);
	}
	
	function lost334(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function act(){//每次行动后判断并记录最大殴熟
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$chprocess();
		
		if(\skillbase\skill_query(334) && $wg > \skillbase\skill_getvalue(334,'cnt')){
			\skillbase\skill_setvalue(334,'cnt',$wg);
		}
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 334){
			
			$var=max($pa['wg'], (int)\skillbase\skill_getvalue($achid,'cnt',$pa));
			$ret = max($ret, $var);
		}
		return $ret;
	}
}

?>