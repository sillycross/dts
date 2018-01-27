<?php

namespace skill314
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach314_name = array(
		0=>'养家糊口',
	);
	
	function init() 
	{
		define('MOD_SKILL314_INFO','achievement;daily;');
		define('MOD_SKILL314_ACHIEVEMENT_ID','14');
		define('DAILY_TYPE314',2);
	}
	
	function acquire314(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(314,'cnt','0',$pa);
	}
	
	function lost314(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize314(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(314,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<10)&&($x>=10)){
			//\cardbase\get_qiegao(100,$pa);
			\achievement_base\ach_create_prize_message($pa, 314, 0, 100);
		}
		
		return $x;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if ( \skillbase\skill_query(314,$pa) && $pd['type']>0 && $pd['hp'] <= 0)
		{
			$x=(int)\skillbase\skill_getvalue(314,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(314,'cnt',$x,$pa);
		}
		
	}	
	
	function show_achievement314($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p314=0;
		else	$p314=$data;	
		$c314=0;
		if ($p314>=10){
			$c314=999;
		}
		include template('MOD_SKILL314_DESC');
	}
}

?>
