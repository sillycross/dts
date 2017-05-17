<?php

namespace skill315
{
	function init() 
	{
		define('MOD_SKILL315_INFO','achievement;daily;');
		define('MOD_SKILL315_ACHIEVEMENT_ID','15');
	}
	
	function acquire315(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(315,'cnt','0',$pa);
	}
	
	function lost315(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize315(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else	$x=base64_decode_number($data);		
		$ox=$x;
		$x+=\skillbase\skill_getvalue(315,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<1)&&($x>=1)){
			\cardbase\get_qiegao(100,$pa);
		}
		
		return base64_encode_number($x,5);		
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(315,$pa))&&($pd['type']==0))
		{
			$x=(int)\skillbase\skill_getvalue(315,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(315,'cnt',$x,$pa);
		}
		$chprocess($pa, $pd, $active);
	}	
	
	function show_achievement315($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p315=0;
		else	$p315=base64_decode_number($data);	
		$c315=0;
		if ($p315>=1){
			$c315=999;
		}
		include template('MOD_SKILL315_DESC');
	}
}

?>
