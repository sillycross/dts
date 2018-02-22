<?php

namespace skill339
{
	//各级要完成的成就名，如果不存在则取低的
	$ach339_name = array(
		1=>'动漫婆罗门',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach339_desc= array(
		1=>'击杀<:threshold:>名真职人',
	);
	
	$ach339_proc_words = '击杀总数';
	
	$ach339_unit = '名';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach339_threshold = array(
		1 => 2,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach339_qiegao_prize = array(
		1 => 180,
	);
	
	//各级给的卡片奖励
	$ach336_card_prize = array(
		1 => 3,
	);
	
	function init() 
	{
		define('MOD_SKILL339_INFO','achievement;daily;');
		define('MOD_SKILL339_ACHIEVEMENT_ID','39');
		define('DAILY_TYPE339',2);
	}
	
	function acquire339(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(339,'cnt',0,$pa);
	}
	
	function lost339(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		if ( \skillbase\skill_query(339,$pa) && $pd['type']==11 && $pd['hp'] <= 0 )
		{
			$x=(int)\skillbase\skill_getvalue(339,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(339,'cnt',$x,$pa);
		}
		
	}	
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 339){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>