<?php

namespace skill321
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach321_name = array(
		0=>'杀人越货',
	);
	
	function init() 
	{
		define('MOD_SKILL321_INFO','achievement;daily;');
		define('MOD_SKILL321_ACHIEVEMENT_ID','21');
		define('DAILY_TYPE321',2);
	}
	
	function acquire321(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(321,'cnt','0',$pa);
	}
	
	function lost321(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize321(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(321,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<2)&&($x>=2)){
			//\cardbase\get_qiegao(110,$pa);
			\achievement_base\ach_create_prize_message($pa, 321, 0, 110);
		}
		
		return $x;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(321,$pa))&&($pd['type']==2))
		{
			$x=(int)\skillbase\skill_getvalue(321,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(321,'cnt',$x,$pa);
		}
		$chprocess($pa, $pd, $active);		
	}	
	
	function show_achievement321($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p321=0;
		else	$p321=$data;	
		$c321=0;
		if ($p321>=2){
			$c321=999;
		}
		include template('MOD_SKILL321_DESC');
	}
}

?>
