<?php

namespace skill325
{
	//各级要完成的成就名，如果不存在则取低的
	$ach325_name = array(
		1=>'常磐的训练师',
		2=>'常磐之心',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach325_desc= array(
		1=>'在0禁使用「精灵球」或者带「小黄的」字样的武器击杀<:threshold:>名NPC',
	);
	
	$ach325_proc_words = '击杀总数';
	
	$ach325_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach325_threshold = array(
		1 => 1,
		2 => 100,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach325_qiegao_prize = array(
		1 => 233,
		2 => 2333,
	);
	
	//各级给的卡片奖励
	$ach325_card_prize = array(
		2 => 119,
	);
	
	function init() 
	{
		define('MOD_SKILL325_INFO','achievement;');
		define('MOD_SKILL325_ACHIEVEMENT_ID','25');
	}
	
	function acquire325(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(325,'cnt','0',$pa);
	}
	
	function lost325(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		eval(import_module('sys'));
		if (!\map\get_area_wavenum() &&  \skillbase\skill_query(325,$pa) && $pd['type']>0 && $pd['hp'] <= 0 && (strpos($pa['attackwith'], '小黄的')!==false || $pa['attackwith']=='精灵球'))
		{
			$x=(int)\skillbase\skill_getvalue(325,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(325,'cnt',$x,$pa);
		}
	}	
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 325){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}

}

?>