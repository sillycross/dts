<?php

namespace skill336
{
	//各级要完成的成就名，如果不存在则取低的
	$ach336_name = array(
		1=>'瞬间爆炸',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach336_desc= array(
		1=>'爆系熟练度达到<:threshold:>点',
	);
	
	$ach336_proc_words = '最高熟练';
	
	$ach336_unit = '点';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach336_threshold = array(
		1 => 300,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach336_qiegao_prize = array(
		1 => 100,
	);
	
	//各级给的卡片奖励
	$ach336_card_prize = array(
		1 => 55,
	);
	
	function init() 
	{
		define('MOD_SKILL336_INFO','achievement;daily;');
		define('MOD_SKILL336_ACHIEVEMENT_ID','36');
		define('DAILY_TYPE336',1);
	}
	
	function acquire336(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(336,'cnt',0,$pa);
	}
	
	function lost336(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function act(){//每次行动后判断并记录最大殴熟
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$chprocess();
		
		if(\skillbase\skill_query(336) && $wd > \skillbase\skill_getvalue(336,'cnt')){
			\skillbase\skill_setvalue(336,'cnt',$wd);
		}
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 336){
			
			$var=max($pa['wd'], (int)\skillbase\skill_getvalue($achid,'cnt',$pa));
			$ret = max($ret, $var);
		}
		return $ret;
	}
}

?>