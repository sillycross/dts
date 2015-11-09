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
	//这个技能已经很变态了
	/*	
	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(238,$pa))&&(check_unlocked238($pa))&&($key=='e'))
		{
			return $chprocess($pa, $pd, $active, $key)*1.25;
		}
		return $chprocess($pa, $pd, $active, $key);
	}
	*/
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
