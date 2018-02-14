<?php

namespace skill352
{
	//各级要完成的成就名，如果不存在则取低的
	$ach352_name = array(
		1=>'中文房间',
		2=>'图灵测试',
		3=>'停机问题',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach352_desc= array(
		1=>'在<span class="clan">除错模式</span>中获得第<:threshold:>名',
	);
	
	$ach352_proc_words = '最佳名次';
	
	$ach352_unit = '';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach352_threshold = array(
		1 => 3,
		2 => 2,
		3 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach352_qiegao_prize = array(
		1 => 200,//真正大量切糕在除错模式结束时赠送了
		2 => 400,
		3 => 600,
	);
	
	//各级给的卡片奖励
	$ach352_card_prize = array(
		2 => 95,
		3 => 96,
	);
	
	function init() 
	{
		define('MOD_SKILL352_INFO','achievement;');
		define('MOD_SKILL352_ACHIEVEMENT_ID','52');
	}
	
	function acquire352(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(352,'cnt',0,$pa);
	}
	
	function lost352(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//判定数据与阈值的关系，这里是小于阈值算达成
	function ach_finalize_check_progress(&$pa, $t, $data, $achid){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(352 == $achid) return $data <= $t;
		else return $chprocess($pa, $t, $data, $achid);
	}
	
	//成就默认值
	function get_achievement_default_var($achid){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($achid);
		if(352==$achid) $ret = 99;
		return $ret;
	}
	
	function gtype1_post_rank_event(&$pa, $cl, $rk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(352,'cnt',$rk,$pa);
		$chprocess($pa, $cl, $rk);
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		eval(import_module('sys'));
		if($achid == 352 && 1==$gametype){
			$var=(int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			if($var) $ret = min($ret, $var);//如果那个值为0则跳过判定，避免非正常结束导致所有玩家的成就清空
		}
		return $ret;
	}
}

?>