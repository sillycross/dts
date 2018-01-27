<?php

namespace skill316
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach316_name = array(
		0=>'幻境无双',
	);

	function init() 
	{
		define('MOD_SKILL316_INFO','achievement;daily;');
		define('MOD_SKILL316_ACHIEVEMENT_ID','16');
		define('DAILY_TYPE316',2);
	}
	
	function acquire316(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(316,'cnt','0',$pa);
	}
	
	function lost316(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function finalize316(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(316,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<200)&&($x>=200)){
			//\cardbase\get_qiegao(250,$pa);
			\achievement_base\ach_create_prize_message($pa, 316, 0, 250);
		}
		
		return $x;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ( \skillbase\skill_query(316,$pa) && $pd['type']==90 && $pd['hp'] <= 0)
		{
			$x=(int)\skillbase\skill_getvalue(316,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(316,'cnt',$x,$pa);
		}
		$chprocess($pa, $pd, $active);
	}	
	
	function show_achievement316($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p316=0;
		else	$p316=$data;	
		$c316=0;
		if ($p316>=200){
			$c316=999;
		}
		include template('MOD_SKILL316_DESC');
	}
}

?>
