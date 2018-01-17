<?php

namespace skill340
{
	//各级要完成的成就名，如果不存在则取低的
	$ach340_name = array(
		1=>'长江后浪',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach340_desc= array(
		1=>'击杀<:threshold:>名电波幽灵',
	);
	
	$ach340_proc_words = '击杀总数';
	
	$ach340_unit = '名';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach340_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach340_qiegao_prize = array(
		1 => 220,
	);
	
	function init() 
	{
		define('MOD_SKILL340_INFO','achievement;daily;');
		define('MOD_SKILL340_ACHIEVEMENT_ID','40');
		define('DAILY_TYPE340',2);
	}
	
	function acquire340(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(340,'cnt',0,$pa);
	}
	
	function lost340(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		if ( \skillbase\skill_query(340,$pa) && $pd['type']==45 && $pd['hp'] <= 0 )
		{
			$x=(int)\skillbase\skill_getvalue(340,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(340,'cnt',$x,$pa);
		}
		
	}	
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 340){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>