<?php

namespace skill368
{
	//各级要完成的成就名，如果不存在则取低的
	$ach368_name = array(
		1=>'屠杀之舞',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach368_desc= array(
		1=>'获胜时场上无存活NPC且非死斗。仅限标准、自选、随机和荣耀模式',
	);
	
	$ach368_proc_words = '获得纪录';
	
	$ach368_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach368_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach368_qiegao_prize = array(
		1 => 9999,
	);
	
	//各级给的卡片奖励
	$ach368_card_prize = array(
		1 => 167,
	);
	
	function init() 
	{
		define('MOD_SKILL368_INFO','achievement;');
		define('MOD_SKILL368_ACHIEVEMENT_ID','68');
	}
	
	function acquire368(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(368,'cnt',0,$pa);
	}
	
	function lost368(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 368){
			eval(import_module('sys'));
			if(\sys\is_winner($pa['name'],$winner) && $gamestate < 50) {
				//判定场上是否没有存活着的NPC了
				$result = $db->query("SELECT pid,hp FROM {$tablepre}players WHERE type > 0 AND hp > 0");
				if(!$db->num_rows($result))
					$ret += 1;
			}
		}
		return $ret;
	}
}

?>