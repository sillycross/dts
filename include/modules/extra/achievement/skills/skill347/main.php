<?php

namespace skill347
{
	//各级要完成的成就名，如果不存在则取低的
	$ach347_name = array(
		1=>'我能反杀',
		2=>'先攻归你',
		3=>'正义可能迟到',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach347_desc= array(
		1=>'在开场超过10分钟且至少有1名存活玩家时入场并获胜<:threshold:>次。房间外或荣耀、极速模式才能完成',
	);
	
	$ach347_proc_words = '当前纪录';
	
	$ach347_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach347_threshold = array(
		1 => 1,
		2 => 5,
		3 => 20,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach347_qiegao_prize = array(
		1 => 270,
		2 => 2001,
		3 => 4800,
	);
	
	$ach347_card_prize = array(
		3 => 101,
	);
	
	function init() 
	{
		define('MOD_SKILL347_INFO','achievement;');
		define('MOD_SKILL347_ACHIEVEMENT_ID','47');
	}
	
	function acquire347(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		//\skillbase\skill_setvalue(347,'cnt','0',$pa);
		$valid = 0;
		if($now - $starttime >= 600 && $alivenum > 1) $valid = 1;
		\skillbase\skill_setvalue(347,'valid',$valid,$pa);
	}
	
	function lost347(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
//	//游戏结束时判定
//	function post_winnercheck_events($wn){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$chprocess($wn);
//		if(!$wn || strpos($wn,',')!==false) return;//无人获胜或者团队获胜则不判定
//		$pa = \player\fetch_playerdata($wn);
//		if(!$pa) return;
//		if (\skillbase\skill_query(347,$pa) && 1==\skillbase\skill_getvalue(347,'valid',$pa)){
//			\skillbase\skill_setvalue(347,'cnt',1,$pa);
//			eval(import_module('sys'));
//			$pdata_pool[$pa['pid']] = $pa;//有点蛋疼
//			//\player\player_save($pa);
//		}
//	}	
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 347){
			eval(import_module('sys'));
			if(\sys\is_winner($pa['name'],$winner) && 1==\skillbase\skill_getvalue(347,'valid',$pa)) $ret += 1;
		}
		return $ret;
	}
}

?>