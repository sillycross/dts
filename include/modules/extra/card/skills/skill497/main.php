<?php

namespace skill497
{
	function init() 
	{
		define('MOD_SKILL497_INFO','card;unique;');
	}
	
	function acquire497(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost497(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked497(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function sk244_get_factor_sum(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(497,$pa) && check_unlocked497($pa)){
			if($pa['dmg_dealt'] % 2) $pa['dmg_dealt']++;
		}
		return $chprocess($pa);
	}
}

?>