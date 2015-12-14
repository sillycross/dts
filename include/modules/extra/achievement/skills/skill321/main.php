<?php

namespace skill321
{
	function init() 
	{
		define('MOD_SKILL321_INFO','achievement;daily;');
		define('MOD_SKILL321_ACHIEVEMENT_ID','21');
	}
	
	function acquire321(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(321,'cnt','0',$pa);
	}
	
	function lost321(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ((!in_array($gametype,$ach_ignore_mode))&&(!\skillbase\skill_query(321,$pa))) //也可以做一些只有房间模式有效的成就
			\skillbase\skill_acquire(321,$pa);
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function finalize321(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else	$x=base64_decode_number($data);		
		$ox=$x;
		$x+=\skillbase\skill_getvalue(321,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<2)&&($x>=2)){
			\cardbase\get_qiegao(110,$pa);
		}
		
		return base64_encode_number($x,5);		
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		eval(import_module('cardbase','sys','logger','map'));
		if ((\skillbase\skill_query(321,$pa))&&($pd['type']==2))
		{
			$x=(int)\skillbase\skill_getvalue(321,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(321,'cnt',$x,$pa);
		}
	}	
	
	function show_achievement321($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p321=0;
		else	$p321=base64_decode_number($data);	
		$c321=0;
		if ($p321>=2){
			$c321=999;
		}
		include template('MOD_SKILL321_DESC');
	}
}

?>
