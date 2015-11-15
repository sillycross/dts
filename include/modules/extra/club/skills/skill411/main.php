<?php

namespace skill411
{
	function init() 
	{
		define('MOD_SKILL411_INFO','club;hidden;');
	}
	
	function acquire411(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost411(&$pa)
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
		if (\skillbase\skill_query(411,$pa)){
			$pa['wp']+=rand(2,4);
			$pa['wk']+=rand(2,4);
			$pa['wc']+=rand(2,4);
			$pa['wg']+=rand(2,4);
			$pa['wd']+=rand(2,4);
			$pa['wf']+=rand(2,4);
		}
		$chprocess($pa);
	}
}

?>
