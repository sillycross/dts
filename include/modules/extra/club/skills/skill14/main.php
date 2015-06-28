<?php

namespace skill14
{
	function init() 
	{
		define('MOD_SKILL14_INFO','club;hidden;');
	}
	
	function acquire14(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['wk']+=25;
	}
	
	function lost14(&$pa)
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
		if (\skillbase\skill_query(14,$pa)) $pa['wk']+=rand(4,6);
		$chprocess($pa);
	}
}

?>
