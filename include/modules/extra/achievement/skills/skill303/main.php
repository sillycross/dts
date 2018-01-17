<?php

namespace skill303
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach303_name = array(
		0=>'篝火的引导',
		1=>'世界的树形图',
		2=>'地=月',
	);
	
	function init() 
	{
		define('MOD_SKILL303_INFO','achievement;');
		define('MOD_SKILL303_ACHIEVEMENT_ID','3');
	}
	
	function acquire303(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(303,'cnt','0',$pa);
	}
	
	function lost303(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize303(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(303,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<1)&&($x>=1)){
			//\cardbase\get_qiegao(150,$pa);
			\achievement_base\ach_create_prize_message($pa, 303, 0, 150);
		}
		if (($ox<5)&&($x>=5)){
			//\cardbase\get_qiegao(500,$pa);
			\achievement_base\ach_create_prize_message($pa, 303, 1, 500);
		}
		if (($ox<30)&&($x>=30)){
			//\cardbase\get_qiegao(2500,$pa);
			\achievement_base\ach_create_prize_message($pa, 303, 2, 2500);
		}
		
		return $x;
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','map'));
		if ($itm0=="【KEY系燃烧弹】"){
			$x=(int)\skillbase\skill_getvalue(303,'cnt');
			$x++;
			\skillbase\skill_setvalue(303,'cnt',$x);
		}
		$chprocess();	
	}

	function show_achievement303($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p303=0;
		else	$p303=$data;	
		$c303=0;
		if ($p303>=30){
			$c303=999;
		}else if ($p303>=5){
			$c303=2;
		}else if ($p303>=1){
			$c303=1;
		}
		include template('MOD_SKILL303_DESC');
	}
}

?>
