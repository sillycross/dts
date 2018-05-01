<?php

namespace skill304
{
	//各级要完成的成就名，如果不存在则取低的
	$ach304_name = array(
		1=>'不屈的生命',
		2=>'那种话最讨厌了',
		3=>'明亮的未来',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach304_desc= array(
		1=>'合成【KEY系生命弹】或【KEY系未来弹】共计<:threshold:>次',
	);
	
	$ach304_proc_words = '目前进度';
	
	$ach304_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach304_threshold = array(
		1 => 1,
		2 => 5,
		3 => 15,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach304_qiegao_prize = array(
		1 => 300,
		2 => 1200,
		3 => 2700,
	);
	
	//各级给的卡片奖励
	$ach304_card_prize = array(
		3 => 87,
	);
	
	function init() 
	{
		define('MOD_SKILL304_INFO','achievement;');
		define('MOD_SKILL304_ACHIEVEMENT_ID','4');
	}
	
	function acquire304(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(304,'cnt','0',$pa);
	}
	
	function lost304(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (($itm0=="【KEY系生命弹】" || $itm0=="【KEY系未来弹】") && \skillbase\skill_query(304)){
			$cnt = (int)\skillbase\skill_getvalue(304,'cnt');
			\skillbase\skill_setvalue(304,'cnt',$cnt + 1);
		}
		$chprocess();	
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 304){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>