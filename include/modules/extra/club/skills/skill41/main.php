<?php

namespace skill41
{
	function init() 
	{
		define('MOD_SKILL41_INFO','club;');
	}
	
	function acquire41(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(41,'u','0',$pa);	//是否已经被解锁
	}
	
	function lost41(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function unlock41(&$pa);
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(41,'u','1',$pa);
	}
	
	function relock41(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(41,'u','0',$pa);
	}
	
	function check_unlocked41(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_getvalue(41,'u',$pa)=='1') return 1; else return 0;
	}
	
	
	
	
}

?>
