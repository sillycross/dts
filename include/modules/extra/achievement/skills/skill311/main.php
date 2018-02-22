<?php

namespace skill311
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach311_name = array(
		0=>'Run With Wolves',
		1=>'Day Game',
		2=>'<font style="font-size:11pt">Thousand Enemies</font>',
	);
	
	function init() 
	{
		define('MOD_SKILL311_INFO','achievement;');
		define('MOD_SKILL311_ACHIEVEMENT_ID','11');
	}
	
	function acquire311(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(311,'cnt','0',$pa);
	}
	
	function lost311(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize311(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(311,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<10)&&($x>=10)){
			//\cardbase\get_qiegao(200,$pa);
			\achievement_base\ach_create_prize_message($pa, 311, 0, 200);
		}
		if (($ox<100)&&($x>=100)){
			//\cardbase\get_qiegao(1200,$pa);
			\achievement_base\ach_create_prize_message($pa, 311, 1, 1200);
		}
		if (($ox<1000)&&($x>=1000)){
			//\cardbase\get_qiegao(4000,$pa);
			\achievement_base\ach_create_prize_message($pa, 311, 2, 4000);
		}
		
		return $x;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(311,$pa))&&($pd['type']==0))
		{
			$x=(int)\skillbase\skill_getvalue(311,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(311,'cnt',$x,$pa);
		}
		$chprocess($pa, $pd, $active);
	}	
	
	function show_achievement311($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p311=0;
		else	$p311=$data;	
		$c311=0;
		if ($p311>=1000){
			$c311=999;
		}else if ($p311>=100){
			$c311=2;
		}else if ($p311>=10){
			$c311=1;
		}
		include template('MOD_SKILL311_DESC');
	}
}

?>
