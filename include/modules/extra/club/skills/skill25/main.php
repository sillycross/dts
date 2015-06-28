<?php

namespace skill25
{
	function init() 
	{
		define('MOD_SKILL25_INFO','club;');
	}
	
	function acquire25(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost25(&$pa)
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
	
	function check_unlocked25(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=3;
	}
	
	function get_ex_inf_dmg_punish(&$pa, &$pd, $active, $key)
	{	
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(25,$pa) && check_unlocked25($pa))
			return $chprocess($pa, $pd, $active, $key)+0.5;
		else	return $chprocess($pa, $pd, $active, $key);
	}
	
	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(25,$pa) && check_unlocked25($pa))
			return $chprocess($pa, $pd, $active, $key)*1.15;
		else	return $chprocess($pa, $pd, $active, $key);
	}
}

?>
