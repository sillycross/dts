<?php

namespace skill304
{
	function init() 
	{
		define('MOD_SKILL304_INFO','achievement;');
		define('MOD_SKILL304_ACHIEVEMENT_ID','4');
	}
	
	function acquire304(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(304,'cnt','0',$pa);
	}
	
	function lost304(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize304(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else	$x=base64_decode_number($data);		
		$ox=$x;
		$x+=\skillbase\skill_getvalue(304,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<1)&&($x>=1)){
			\cardbase\get_qiegao(100,$pa);
		}
		if (($ox<5)&&($x>=5)){
			\cardbase\get_qiegao(400,$pa);
		}
		if (($ox<30)&&($x>=30)){
			\cardbase\get_qiegao(1200,$pa);
			\cardbase\get_card(87,$pa);
		}
		
		return base64_encode_number($x,5);		
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','map'));
		if ($itm0=="【KEY系生命弹】"){
			$x=(int)\skillbase\skill_getvalue(304,'cnt');
			$x++;
			\skillbase\skill_setvalue(304,'cnt',$x);
		}
		$chprocess();	
	}

	function show_achievement304($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p304=0;
		else	$p304=base64_decode_number($data);	
		$c304=0;
		if ($p304>=30){
			$c304=999;
		}else if ($p304>=5){
			$c304=2;
		}else if ($p304>=1){
			$c304=1;
		}
		include template('MOD_SKILL304_DESC');
	}
}

?>
