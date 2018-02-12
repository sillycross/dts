<?php

namespace skill337
{
	//各级要完成的成就名，如果不存在则取低的
	$ach337_name = array(
		1=>'唯心主义',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach337_desc= array(
		1=>'灵系熟练度达到<:threshold:>点',
	);
	
	$ach337_proc_words = '最高熟练';
	
	$ach337_unit = '点';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach337_threshold = array(
		1 => 300,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach337_qiegao_prize = array(
		1 => 100,
	);
	
	//各级给的卡片奖励
	$ach337_card_prize = array(
		1 => 161,
	);
	
	function init() 
	{
		define('MOD_SKILL337_INFO','achievement;daily;');
		define('MOD_SKILL337_ACHIEVEMENT_ID','37');
		define('DAILY_TYPE337',1);
	}
	
	function acquire337(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(337,'cnt',0,$pa);
	}
	
	function lost337(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function act(){//每次行动后判断并记录最大殴熟
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$chprocess();
		
		if(\skillbase\skill_query(337) && $wf > \skillbase\skill_getvalue(337,'cnt')){
			\skillbase\skill_setvalue(337,'cnt',$wf);
		}
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 337){
			
			$var=max($pa['wf'], (int)\skillbase\skill_getvalue($achid,'cnt',$pa));
			$ret = max($ret, $var);
		}
		return $ret;
	}
}

?>