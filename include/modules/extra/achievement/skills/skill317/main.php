<?php

namespace skill317
{
	function init() 
	{
		define('MOD_SKILL317_INFO','achievement;daily;');
		define('MOD_SKILL317_ACHIEVEMENT_ID','17');
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
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ((!in_array($gametype,$ach_ignore_mode))&&(!\skillbase\skill_query(317,$pa))) //也可以做一些只有房间模式有效的成就
			\skillbase\skill_acquire(317,$pa);
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function finalize317(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='VWXYZ') return 'VWXYZ';
		if ($data=='') return 'VWXYZ';
		if ($data=='')					
			$x=0;						
		else	$x=base64_decode_number($data);		
		$ox=$x;
		$x+=\skillbase\skill_getvalue(317,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<1)&&($x>=1)){
			\cardbase\get_qiegao(233,$pa);
		}
		
		return base64_encode_number($x,5);		
	}

	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','map'));
		if (strpos($itmsk0,'x') !== false){
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
		else	$p317=base64_decode_number($data);	
		$c317=0;
		if ($p317>=1){
			$c317=999;
		}
		include template('MOD_SKILL317_DESC');
	}
}

?>
