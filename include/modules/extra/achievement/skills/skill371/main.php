<?php

namespace skill371
{
	//各级要完成的成就名，如果不存在则取低的
	$ach371_name = array(
		1=>'你好，幻境',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach371_desc= array(
		1=>'完成<span class="red b">教程模式</span><:threshold:>次',
	);
	
	$ach371_proc_words = '完成次数';
	
	$ach371_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach371_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach371_qiegao_prize = array(
		1 => 1010,
	);
	
	//各级给的卡片奖励
	$ach371_card_prize = array(
		1 => 199,
	);
	
	function init() 
	{
		define('MOD_SKILL371_INFO','achievement;');
		define('MOD_SKILL371_ACHIEVEMENT_ID','71');
	}
	
	function acquire371(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(371,'cnt',0,$pa);
	}
	
	function lost371(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//在教程模式获胜时直接判定
	function tutorial_win(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('sys','player'));
		if(17==$gametype) {
			$udata = fetch_udata('*', "username='$name'")[0];
			
			\achievement_base\update_achievements_by_udata($udata, $sdata);
			$udata['u_achievements'] = \achievement_base\encode_achievements($udata['u_achievements']);
			update_udata($udata, "username='$name'");

		}
		return;
	}
	
	//注意这个不是在游戏结束时判定的（教程房永远不会结束）
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 371){
			eval(import_module('sys','player'));
			if(17==$gametype && 4==$state) $ret += 1;
		}
		return $ret;
	}
}

?>