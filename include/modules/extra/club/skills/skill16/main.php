<?php

namespace skill16
{
	function init() 
	{
		define('MOD_SKILL16_INFO','club;hidden;');
	}
	
	function acquire16(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['wc']+=25;
	}
	
	function lost16(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(16,$pa)) $pa['wc']+=rand(4,6);
		$chprocess($pa);
	}
}

?>
