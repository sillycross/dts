<?php

namespace skill374
{
	//各级要完成的成就名，如果不存在则取低的
	$ach374_name = array(
		1=>'歪嘴龙王',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach374_desc= array(
		1=>'使用C/M级卡片获胜<:threshold:>次。只能在自选、随机、荣耀、极速模式完成',
	);
	
	$ach374_proc_words = '当前纪录';
	
	$ach374_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach374_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach374_qiegao_prize = array(
		1 => 200,
	);
	
	//各级给的卡片奖励
	$ach374_card_prize = array(
		1 => 23,
	);
	
	function init() 
	{
		define('MOD_SKILL374_INFO','achievement;daily;');
		define('MOD_SKILL374_ACHIEVEMENT_ID','74');
		define('DAILY_TYPE374',3);
	}
	
	function acquire374(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost374(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_card374(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		if($pa['type']) return false;
		$rare_a = $cards[$pa['card']]['rare'];
		return in_array($rare_a,array('C','M'));
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		eval(import_module('sys'));
		if($achid == 374 && check_card374($pa) && \sys\is_winner($pa['name'],$winner)){
			$ret += 1;
		}
		return $ret;
	}
}

?>