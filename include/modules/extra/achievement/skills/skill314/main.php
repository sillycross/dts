<?php

namespace skill314
{
	function init() 
	{
		define('MOD_SKILL314_INFO','achievement;daily;');
		define('MOD_SKILL314_ACHIEVEMENT_ID','14');
	}
	
	function acquire314(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(314,'cnt','0',$pa);
	}
	
	function lost314(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize314(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else	$x=base64_decode_number($data);		
		$ox=$x;
		$x+=\skillbase\skill_getvalue(314,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<10)&&($x>=10)){
			\cardbase\get_qiegao(100,$pa);
		}
		
		return base64_encode_number($x,5);		
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if ( \skillbase\skill_query(314,$pa) && $pd['type']>0 && $pd['hp'] <= 0)
		{
			$x=(int)\skillbase\skill_getvalue(314,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(314,'cnt',$x,$pa);
		}
		
	}	
	
	function show_achievement314($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p314=0;
		else	$p314=base64_decode_number($data);	
		$c314=0;
		if ($p314>=10){
			$c314=999;
		}
		include template('MOD_SKILL314_DESC');
	}
}

?>
