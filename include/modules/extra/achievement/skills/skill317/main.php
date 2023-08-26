<?php

namespace skill317
{
	function init() 
	{
		define('MOD_SKILL317_INFO','achievement;daily;');
		define('MOD_SKILL317_ACHIEVEMENT_ID','17');
		define('MOD_SKILL317_ABANDONED','1');//已废弃
	}
	
	function acquire317(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(317,'cnt','0',$pa);
	}
	
	function lost317(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize317(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(317,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<1)&&($x>=1)){
			\cardbase\get_qiegao(233,$pa);
		}
		
		return $x;
	}

	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','map'));
		if (\itemmain\check_in_itmsk('x', $itmsk0)){
			$x=(int)\skillbase\skill_getvalue(317,'cnt');
			$x++;
			\skillbase\skill_setvalue(317,'cnt',$x);
		}
		$chprocess();	
	}
	
	function show_achievement317($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p317=0;
		else	$p317=$data;	
		$c317=0;
		if ($p317>=1){
			$c317=999;
		}
		include template('MOD_SKILL317_DESC');
	}
}

?>
