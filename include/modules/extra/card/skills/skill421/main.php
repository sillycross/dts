<?php

namespace skill421
{
	function init() 
	{
		define('MOD_SKILL421_INFO','card;hidden;');
	}
	
	function acquire421(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost421(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked421(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_edible_hpup(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(421,$pa)) return round($chprocess($theitem)*1.2);
		return $chprocess($theitem);
	}
	
	function get_edible_spup(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(421,$pa)) return round($chprocess($theitem)*1.2);
		return $chprocess($theitem);
	}
}

?>
