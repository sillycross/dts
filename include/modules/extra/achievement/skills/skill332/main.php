<?php

namespace skill332
{
	$ach332_name = array(
		0=>'怼人宝具',
	);
	
	$ach332_threshold = array(
		1 => 1,
		999 => NULL
	);
	$ach332_qiegao_prize = array(
		1 => 300,
		999 => NULL
	);
	
	function init() 
	{
		define('MOD_SKILL332_INFO','achievement;daily;');
		define('MOD_SKILL332_ACHIEVEMENT_ID','32');
		define('DAILY_TYPE332',3);
	}
	
	function acquire332(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(332,'cnt','0',$pa);
	}
	
	function lost332(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('map'));
		if (\skillbase\skill_query(332,$pa) && $pd['type']>0 && $pa['attackwith']=='精灵球' && $areanum==0 && (!isset($pa['bskill']) || $pa['bskill']==0))
		{
			$x=(int)\skillbase\skill_getvalue(332,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(332,'cnt',$x,$pa);
		}
		$chprocess($pa, $pd, $active);
	}	
}

?>