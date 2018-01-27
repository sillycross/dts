<?php

namespace skill341
{
	//各级要完成的成就名，如果不存在则取低的
	$ach341_name = array(
		1=>'危险探测器',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach341_desc= array(
		1=>'击杀<:threshold:>名杏仁豆腐',
	);
	
	$ach341_proc_words = '击杀总数';
	
	$ach341_unit = '名';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach341_threshold = array(
		1 => 2,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach341_qiegao_prize = array(
		1 => 200,
	);
	
	function init() 
	{
		define('MOD_SKILL341_INFO','achievement;daily;');
		define('MOD_SKILL341_ACHIEVEMENT_ID','41');
		define('DAILY_TYPE341',2);
	}
	
	function acquire341(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(341,'cnt',0,$pa);
	}
	
	function lost341(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ( \skillbase\skill_query(341,$pa) && $pd['type']==5 )
		{
			$x=(int)\skillbase\skill_getvalue(341,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(341,'cnt',$x,$pa);
		}
		$chprocess($pa, $pd, $active);		
	}	
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 341){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>