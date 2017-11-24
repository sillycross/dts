<?php

namespace skill229
{
	function init() 
	{
		define('MOD_SKILL229_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[229] = '神功';
	}
	
	function acquire229(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost229(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked229(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=19;
	}
	
	function calculate_attack_weapon_skill_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(229,$pa))&&(check_unlocked229($pa))) return (1+$chprocess($pa,$pd,$active));
		return $chprocess($pa,$pd,$active);
	}
}

?>
