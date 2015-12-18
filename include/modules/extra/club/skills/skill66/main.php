<?php

namespace skill66
{
	function init() 
	{
		define('MOD_SKILL66_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[66] = '人杰';
	}
	
	function acquire66(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost66(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked66(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=19;
	}
	
	function get_skill(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(66,$pa) || !check_unlocked66($pa)) return $chprocess($pa, $pd, $active);
		eval(import_module('weapon'));
		$max_skill=0;
		foreach (array_unique(array_values($skillinfo)) as $key)
			$max_skill=max($max_skill,\weapon\get_skill_by_kind($pa, $pd, $active, $key));
		return $max_skill;
	}
}

?>
