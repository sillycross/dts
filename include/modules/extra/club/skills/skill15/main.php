<?php

namespace skill15
{
	function init() 
	{
		define('MOD_SKILL15_INFO','club;hidden;');
	}
	
	function acquire15(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['wg']+=25;
	}
	
	function lost15(&$pa)
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
		if (\skillbase\skill_query(15,$pa)) $pa['wg']+=rand(4,6);
		$chprocess($pa);
	}
}

?>
