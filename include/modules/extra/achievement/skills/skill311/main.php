<?php

namespace skill311
{
	function init() 
	{
		define('MOD_SKILL311_INFO','achievement;');
		define('MOD_SKILL311_ACHIEVEMENT_ID','11');
	}
	
	function acquire311(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(311,'cnt','0',$pa);
	}
	
	function lost311(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ((!in_array($gametype,$ach_ignore_mode))&&(!\skillbase\skill_query(311,$pa))) //也可以做一些只有房间模式有效的成就
			\skillbase\skill_acquire(311,$pa);
		$chprocess($pa);
	}
	
	function finalize311(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else	$x=base64_decode_number($data);		
		$ox=$x;
		$x+=\skillbase\skill_getvalue(311,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<10)&&($x>=10)){
			\cardbase\get_qiegao(200,$pa);
		}
		if (($ox<100)&&($x>=100)){
			\cardbase\get_qiegao(1200,$pa);
		}
		if (($ox<1000)&&($x>=1000)){
			\cardbase\get_qiegao(4000,$pa);
		}
		
		return base64_encode_number($x,5);		
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(311,$pa))&&($pd['type']==0))
		{
			$x=(int)\skillbase\skill_getvalue(311,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(311,'cnt',$x,$pa);
		}
		$chprocess($pa, $pd, $active);
	}	
	
	function show_achievement311($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p311=0;
		else	$p311=base64_decode_number($data);	
		$c311=0;
		if ($p311>=1000){
			$c311=999;
		}else if ($p311>=100){
			$c311=2;
		}else if ($p311>=10){
			$c311=1;
		}
		include template('MOD_SKILL311_DESC');
	}
}

?>
