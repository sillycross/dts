<?php

namespace skill17
{
	function init() 
	{
		define('MOD_SKILL17_INFO','club;hidden;');
	}
	
	function acquire17(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['wd']+=25;
	}
	
	function lost17(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(17,$pa)) $pa['wd']+=rand(5,7);
		$chprocess($pa);
	}
}

?>
