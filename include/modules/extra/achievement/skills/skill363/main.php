<?php

namespace skill363
{
	//各级要完成的成就名，如果不存在则取低的
	$ach363_name = array(
		0=>'幻境的最终战士',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach363_desc= array(
		1=>'用卡册中的所有卡片获胜',
	);
	
	$ach363_proc_words = '完成版本';
	
	$ach363_unit = '';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	//全局成就此条是废弃的
	$ach363_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的因果奖励
	$ach363_karma_prize = array(
		1 => 100,
		2 => 5,
	);
	
	$ach363_unique_prize_desc = '<font color="olive">首次完成时获得<span class="cyan b">'.$ach363_karma_prize[1].'因果</span>，之后每次完成获得<span class="cyan b">'.$ach363_karma_prize[2].'因果</span></font>';
	
	function init() 
	{
		define('MOD_SKILL363_INFO','achievement;global;');
		define('MOD_SKILL363_ACHIEVEMENT_ID','63');
	}
	
	function acquire363(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost363(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//怕有兼容性问题，保留着吧
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $chprocess($pa, $data, $achid);
	}
	
	//全局成就是否满足条件
	//而是新完成还是已完成，是在ach_global_ach_check()根据版本号判定的
	function ach_global_ach_check_progress(&$ud, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($ud, $achid);
		if(363 == $achid){
			//$var326 = \skill326\cardlist_decode326($ud['u_achievements'][326]);
			$var326 = $ud['u_achievements'][326];
			eval(import_module('cardbase'));
			$flag = 1;
			foreach($cards as $ci => $cv){
				if('hidden' != $cv['pack'] && \cardbase\check_pack_availble($cv['pack']) && empty($cv['ignore_global_ach'])){//不判定隐藏卡（软件工程师等）、没开放的卡以及标明不参与终身成就判定的卡
					if(!in_array($ci, $var326)) {
						$flag = 0;
						break;
					}
				}
			}
			$ret = $flag;
		}
		return $ret;
	}
	
	function ach_global_ach_finalize_save_getnum($data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($data, $achid);
		if(363 == $achid){
			eval(import_module('cardbase'));
			$ret = 0;
			foreach($cards as $ci => $cv){
				if('hidden' != $cv['pack'] && \cardbase\check_pack_availble($cv['pack'])){
					$ret ++;
				}
			}
		}
		return $ret;
	}
}

?>