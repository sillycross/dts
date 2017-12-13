<?php

namespace skill320
{
	function init() 
	{
		define('MOD_SKILL320_INFO','achievement;daily;');
		define('MOD_SKILL320_ACHIEVEMENT_ID','20');
		define('MOD_SKILL320_ABANDONED','1');//已废弃
	}
	
	function acquire320(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(320,'cnt','0',$pa);
	}
	
	function lost320(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize320(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(320,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<1)&&($x>=1)){
			\cardbase\get_qiegao(150,$pa);
		}
		
		return $x;
	}

	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','map'));
		if ($itm0=="【KEY系催泪弹】"){
			$x=(int)\skillbase\skill_getvalue(320,'cnt');
			$x++;
			\skillbase\skill_setvalue(320,'cnt',$x);
		}
		$chprocess();	
	}
	
	function show_achievement320($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p320=0;
		else	$p320=$data;	
		$c320=0;
		if ($p320>=1){
			$c320=999;
		}
		include template('MOD_SKILL320_DESC');
	}
}

?>
