<?php

namespace skill349
{
	//各级要完成的成就名，如果不存在则取低的
	$ach349_name = array(
		1=>'屠戮成性',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach349_desc= array(
		1=>'在冰封墓场、SCP研究设施或者英灵殿战斗击杀<:threshold:>名玩家</span>。房间外或荣耀、SOLO、组队模式才能完成',
	);
	
	$ach349_proc_words = '击杀总数';
	
	$ach349_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach349_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach349_qiegao_prize = array(
		1 => 300,
	);
	
	function init() 
	{
		define('MOD_SKILL349_INFO','achievement;daily;');
		define('MOD_SKILL349_ACHIEVEMENT_ID','49');
		define('DAILY_TYPE349',3);
	}
	
	function acquire349(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(349,'cnt',0,$pa);
	}
	
	function lost349(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		if ( \skillbase\skill_query(349,$pa) && !$pd['type'] && $pd['hp'] <= 0)
		{
			//位于指定地图，且对方为活跃玩家
			if(in_array($pa['pls'], array(26,32,34)) && !$pd['type']){
				$x=(int)\skillbase\skill_getvalue(349,'cnt',$pa);
				$x+=1;
				
				\skillbase\skill_setvalue(349,'cnt',$x,$pa);
			}			
		}
	}	
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 349){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>