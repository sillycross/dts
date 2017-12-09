<?php

namespace skill309
{
	function init() 
	{
		define('MOD_SKILL309_INFO','achievement;');
		define('MOD_SKILL309_ACHIEVEMENT_ID','9');
	}
	
	function acquire309(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(309,'cnt','0',$pa);
	}
	
	function lost309(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize309(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x=\skillbase\skill_getvalue(309,'cnt',$pa);
		if ($x==0) $x=$ox;
		if ($ox!=0) $x=min($x,$ox);
		
		if (($x!=0)&&($x<=900)&&(($ox>900)||($ox==0))){
			\cardbase\get_qiegao(666,$pa);
			\cardbase\get_card(72,$pa);
		}
		
		return $x;
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','map'));
		if ($itm0=="火水木金土符『贤者之石』"){
			if ((int)(\skillbase\skill_getvalue(309,'cnt'))==0)
				\skillbase\skill_setvalue(309,'cnt',$now-$starttime);
		}
		$chprocess();	
	}

	function show_achievement309($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p309=0;
		else	$p309=$data;	
		$c309=0;
		if (($p309<=900)&&($p309!=0)){
			$c309=999;
		}
		include template('MOD_SKILL309_DESC');
	}
}

?>
