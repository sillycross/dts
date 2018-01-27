<?php

namespace skill302
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach302_name = array(
		0=>'永恒世界的住人',
		1=>'幻想世界的往人',
		2=>'永恒的覆唱',
	);
	
	function init() 
	{
		define('MOD_SKILL302_INFO','achievement;');
		define('MOD_SKILL302_ACHIEVEMENT_ID','2');
	}
	
	function acquire302(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(302,'cnt','0',$pa);
	}
	
	function lost302(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	
	function finalize302(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(302,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<1)&&($x>=1)){
			//\cardbase\get_qiegao(50,$pa);
			\achievement_base\ach_create_prize_message($pa, 302, 0, 50);
		}
		if (($ox<5)&&($x>=5)){
			//\cardbase\get_qiegao(300,$pa);
			\achievement_base\ach_create_prize_message($pa, 302, 1, 300);
		}
		if (($ox<30)&&($x>=30)){
			//\cardbase\get_qiegao(1000,$pa);
			//\cardbase\get_card(66,$pa);
			\achievement_base\ach_create_prize_message($pa, 302, 2, 1000, 66);
		}
		
		return $x;
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','map'));
		if ($itm0=="【KEY系催泪弹】"){
			$x=(int)\skillbase\skill_getvalue(302,'cnt');
			$x++;
			\skillbase\skill_setvalue(302,'cnt',$x);
		}
		$chprocess();	
	}

	function show_achievement302($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p302=0;
		else	$p302=$data;	
		$c302=0;
		if ($p302>=30){
			$c302=999;
		}else if ($p302>=5){
			$c302=2;
		}else if ($p302>=1){
			$c302=1;
		}
		include template('MOD_SKILL302_DESC');
	}
}

?>
