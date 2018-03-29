<?php

namespace skill366
{
	$ach366_npcname = '一一五';
	
	//各级要完成的成就名，如果不存在则取低的
	$ach366_name = array(
		1=>'最后一根稻草',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach366_desc= array(
		1=>'击杀活动NPC「'.$ach366_npcname.'」',
	);
	
	$ach366_proc_words = '击杀次数';
	
	$ach366_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach366_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach366_qiegao_prize = array(
		1 => 3600,
	);
	
	function init() 
	{
		define('MOD_SKILL366_INFO','achievement;spec-activity;');
		define('MOD_SKILL366_ACHIEVEMENT_ID','66');
	}
	
	function acquire366(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(366,'cnt','0',$pa);
	}
	
	function lost366(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 366){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
	
	function show_achievement_icon($achid, $c, $top_flag)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($achid, $c, $top_flag);
		if(366 == $achid) {
			$ret = \skill365\get_apf2018_icon($achid, $c, $top_flag);
		}
		return $ret;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill366'));
		if ( \skillbase\skill_query(366,$pa) && $pd['name']==$ach366_npcname && $pd['type'] > 0)
		{
			$x=(int)\skillbase\skill_getvalue(366,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(366,'cnt',$x,$pa);
		}
		$chprocess($pa, $pd, $active);		
	}	
}

?>