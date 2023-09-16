<?php

namespace skill442
{
	function init() 
	{
		define('MOD_SKILL442_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[442] = 'E术';
	}
	
	function acquire442(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost442(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked442(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_weapon_fluc_percentage(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(442,$pa))&&(check_unlocked442($pa)))
			return (1.5*$chprocess($pa, $pd, $active));
		else  return $chprocess($pa, $pd, $active);
	}
}

?>
