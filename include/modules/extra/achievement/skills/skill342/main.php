<?php

namespace skill342
{
	//各级要完成的成就名，如果不存在则取低的
	$ach342_name = array(
		1=>'为了忘却的纪念',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach342_desc= array(
		1=>'击杀<:threshold:>次黑幕 Acg_xilin',
	);
	
	$ach342_proc_words = '击杀总数';
	
	$ach342_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach342_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach342_qiegao_prize = array(
		1 => 200,
	);
	
	//各级给的卡片奖励
	$ach332_card_prize = array(
		1 => 97,
	);
	
	function init() 
	{
		define('MOD_SKILL342_INFO','achievement;daily;');
		define('MOD_SKILL342_ACHIEVEMENT_ID','42');
		define('DAILY_TYPE342',2);
	}
	
	function acquire342(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(342,'cnt',0,$pa);
	}
	
	function lost342(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ( \skillbase\skill_query(342,$pa) && $pd['type']==6 )
		{
			$x=(int)\skillbase\skill_getvalue(342,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(342,'cnt',$x,$pa);
		}
		$chprocess($pa, $pd, $active);		
	}	
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 342){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>