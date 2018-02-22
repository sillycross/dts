<?php

namespace skill308
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach308_name = array(
		0=>'清水池之王',
	);
	
	function init() 
	{
		define('MOD_SKILL308_INFO','achievement;');
		define('MOD_SKILL308_ACHIEVEMENT_ID','8');
	}
	
	function acquire308(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(308,'cnt','0',$pa);
	}
	
	function lost308(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize308(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x=\skillbase\skill_getvalue(308,'cnt',$pa);		
		if ($x==0) $x=$ox;
		if ($ox!=0) $x=min($x,$ox);
		
		if (($x!=0)&&($x<=300)&&(($ox>300)||($ox==0))){
			//\cardbase\get_qiegao(666,$pa);
			\achievement_base\ach_create_prize_message($pa, 308, 0, 666);
		}
		
		return $x;
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','map'));
		if ($itm0=="【KEY系催泪弹】"){
			if ((int)(\skillbase\skill_getvalue(308,'cnt'))==0)
				\skillbase\skill_setvalue(308,'cnt',$now-$starttime);
		}
		$chprocess();	
	}

	function show_achievement308($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p308=0;
		else	$p308=$data;	
		$c308=0;
		if (($p308<=300)&&($p308!=0)){
			$c308=999;
		}
		include template('MOD_SKILL308_DESC');
	}
}

?>
