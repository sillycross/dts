<?php

namespace skill18
{
	function init() 
	{
		define('MOD_SKILL18_INFO','club;hidden;');
	}
	
	function acquire18(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['wf']+=20;
	}
	
	function lost18(&$pa)
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
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(18,$pa)) $pa['wf']+=rand(3,5);
		$chprocess($pa);
	}
}

?>
