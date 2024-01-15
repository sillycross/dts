<?php

namespace skill724
{
	function init()
	{
		define('MOD_SKILL724_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[724] = '越界';
	}
	
	function acquire724(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost724(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked724(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function check_can_enter($pno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(724, $sdata)) return true;
		return $chprocess($pno);
	}
	
}

?>