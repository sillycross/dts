<?php

namespace skill238
{
	function init() 
	{
		define('MOD_SKILL238_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[238] = '过载';
	}
	
	function acquire238(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost238(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked238(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return (($pa['lvl']>=20)||($pa['card']==63)||($pa['card']==64));
	}

	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(238,$pa))&&(check_unlocked238($pa))&&($key=='e'))
		{
			if ($pa['card']==63) return $chprocess($pa, $pd, $active, $key)*1.5;
			if ($pa['card']==64) return $chprocess($pa, $pd, $active, $key)*1.25;
		}
		return $chprocess($pa, $pd, $active, $key);
	}
	
	function get_ex_dmg_restriction(&$pa,&$pd,$active,$key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(238,$pa))&&(check_unlocked238($pa))&&($key=='e'))
		{
			return 0;
		}
		return $chprocess($pa, $pd, $active, $key);
	}
}

?>
