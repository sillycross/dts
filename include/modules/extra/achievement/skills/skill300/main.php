<?php

namespace skill300
{
	function init() 
	{
		define('MOD_SKILL300_INFO','achievement;');
		define('MOD_SKILL300_ACHIEVEMENT_ID','0');
	}
	
	function acquire300(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(300,'cnt','0',$pa);
	}
	
	function lost300(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(300,$pa)) \skillbase\skill_acquire(300,$pa);
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function finalize300(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else	$x=base64_decode_number($data);		
		$x+=\skillbase\skill_getvalue(300,'cnt',$pa);		
		$x=min($x,(1<<30)-1);		
		return base64_encode_number($x,5);		
	}
	
	function edible_recover($itm, $hpup, $spup)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(300))
		{
			$x=(int)\skillbase\skill_getvalue(300,'cnt');
			$x+=$hpup+$spup;
			\skillbase\skill_setvalue(300,'cnt',$x);
		}
		$chprocess($itm,$hpup,$spup);
	}

	function show_achievement300($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$x=0;
		else	$x=base64_decode_number($data);	
		include template('MOD_SKILL300_DESC');
	}
}

?>
