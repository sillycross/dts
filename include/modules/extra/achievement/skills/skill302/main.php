<?php

namespace skill302
{
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
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ((!in_array($gametype,$ach_ignore_mode))&&(!\skillbase\skill_query(302,$pa))) 
			\skillbase\skill_acquire(302,$pa);
		$chprocess($pa);
	}
	
	function finalize302(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else	$x=base64_decode_number($data);		
		$ox=$x;
		$x+=\skillbase\skill_getvalue(302,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<1)&&($x>=1)){
			\cardbase\get_qiegao(50,$pa);
		}
		if (($ox<5)&&($x>=5)){
			\cardbase\get_qiegao(300,$pa);
		}
		if (($ox<30)&&($x>=30)){
			\cardbase\get_qiegao(1000,$pa);
			\cardbase\get_card(66,$pa);
		}
		
		return base64_encode_number($x,5);		
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
		else	$p302=base64_decode_number($data);	
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
