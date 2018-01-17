<?php

namespace skill312
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach312_name = array(
		0=>'谈笑风生',
	);
	
	function init() 
	{
		define('MOD_SKILL312_INFO','achievement;');
		define('MOD_SKILL312_ACHIEVEMENT_ID','12');
	}
	
	function acquire312(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(312,'cnt','0',$pa);
	}
	
	function lost312(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function finalize312(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(312,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<4)&&($x>=4)){
			//\cardbase\get_qiegao(888,$pa);
			//\cardbase\get_card(88,$pa);
			\achievement_base\ach_create_prize_message($pa, 312, 0, 888, 88);
		}
		
		return $x;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(312,$pa))&&($pd['type']==88))
		{
			$x=(int)\skillbase\skill_getvalue(312,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(312,'cnt',$x,$pa);
		}
		$chprocess($pa, $pd, $active);
	}	
	
	function show_achievement312($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p312=0;
		else	$p312=$data;	
		$c312=0;
		if ($p312>=4){
			$c312=999;
		}
		include template('MOD_SKILL312_DESC');
	}
}

?>
