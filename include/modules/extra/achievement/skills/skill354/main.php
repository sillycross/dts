<?php

namespace skill354
{
	//各级要完成的成就名，如果不存在则取低的
	$ach354_name = array(
		1=>'天明的秘剑',
		2=>'最后胜地',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach354_desc= array(
		1=>'合成绝冲大剑【神威】<:threshold:>次',
	);
	
	$ach354_proc_words = '目前进度';
	
	$ach354_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach354_threshold = array(
		1 => 1,
		2 => 3,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach354_qiegao_prize = array(
		1 => 700,
		2 => 2400,
	);
	
	//各级给的卡片奖励
	$ach354_card_prize = array(
		2 => 42,
	);
	
	function init() 
	{
		define('MOD_SKILL354_INFO','achievement;');
		define('MOD_SKILL354_ACHIEVEMENT_ID','54');
	}
	
	function acquire354(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(354,'cnt','0',$pa);
	}
	
	function lost354(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if ($itm0=='绝冲大剑【神威】' && \skillbase\skill_query(354)){
			$cnt = (int)\skillbase\skill_getvalue(354,'cnt');
			\skillbase\skill_setvalue(354,'cnt',$cnt + 1);
		}
		$chprocess();	
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 354){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>