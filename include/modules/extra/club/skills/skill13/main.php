<?php

namespace skill13
{
	function init() 
	{
		define('MOD_SKILL13_INFO','club;hidden;');
	}
	
	function acquire13(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['wp']+=25;
	}
	
	function lost13(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(13,$pa)) $pa['wp']+=rand(4,6);
		$chprocess($pa);
	}
}

?>
