<?php

namespace skill25
{
	function init() 
	{
		define('MOD_SKILL25_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[25] = '圣光';
	}
	
	function acquire25(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost25(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked25(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=15;
	}
	
	function get_ex_inf_dmg_punish(&$pa, &$pd, $active, $key)
	{	
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(25,$pa) && check_unlocked25($pa))
			return $chprocess($pa, $pd, $active, $key)+0.5;
		else	return $chprocess($pa, $pd, $active, $key);
	}
	
	function calculate_ex_attack_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(25,$pa) && check_unlocked25($pa))
		{
			$r=Array(1.25);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
//	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if (\skillbase\skill_query(25,$pa) && check_unlocked25($pa))
//			return $chprocess($pa, $pd, $active, $key)*1.15;
//		else	return $chprocess($pa, $pd, $active, $key);
//	}
}

?>
