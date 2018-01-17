<?php

namespace skill325
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach325_name = array(
		0=>'常磐的训练师',
		1=>'常磐之心',
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
	
	function finalize325(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;	
		
		$z=(int)\skillbase\skill_getvalue(325,'cnt',$pa);
		$ox=$x;
		$x=$ox+$z;
		
		if (($ox<1)&&($x>=1)){
			//\cardbase\get_qiegao(233,$pa);
			\achievement_base\ach_create_prize_message($pa, 325, 0, 233);
		}
		
		if (($ox<100)&&($x>=100)){
			//\cardbase\get_qiegao(2333,$pa);
			//\cardbase\get_card(119,$pa);
			\achievement_base\ach_create_prize_message($pa, 325, 1, 2333, 119);
		}
		
		return $x;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('map'));
		if (\skillbase\skill_query(325,$pa) && $pd['type']>0 && $pa['attackwith']=='精灵球' && $areanum==0 && (!isset($pa['bskill']) || $pa['bskill']==0))
		{
			$x=(int)\skillbase\skill_getvalue(325,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(325,'cnt',$x,$pa);
		}
		$chprocess($pa, $pd, $active);
	}	
	
	function show_achievement325($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p325=0;
		else	$p325=$data;	
		$c325=0;
		if ($p325>=100)
			$c325=999;
		else if ($p325>=1)
			$c325=1;
		include template('MOD_SKILL325_DESC');
	}
}

?>
