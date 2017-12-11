<?php

namespace skill319
{
	function init() 
	{
		define('MOD_SKILL319_INFO','achievement;daily;');
		define('MOD_SKILL319_ACHIEVEMENT_ID','19');
		define('MOD_SKILL319_ABANDONED','1');//已废弃
	}
	
	function acquire319(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(319,'cnt','0',$pa);
	}
	
	function lost319(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize319(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(319,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<15)&&($x>=15)){
			\cardbase\get_qiegao(485,$pa);
			\cardbase\get_card(12,$pa);
		}
		
		return $x;
	}

	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','map'));
		if (strlen($itmk0)>=4){//其实这很不严谨！
			$x=(int)\skillbase\skill_getvalue(319,'cnt');
			$x++;
			\skillbase\skill_setvalue(319,'cnt',$x);
		}
		$chprocess();	
	}
	
	function show_achievement319($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p319=0;
		else	$p319=$data;	
		$c319=0;
		if ($p319>=15){
			$c319=999;
		}
		include template('MOD_SKILL319_DESC');
	}
}

?>
