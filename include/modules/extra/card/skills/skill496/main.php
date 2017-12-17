<?php

namespace skill496
{
	function init() 
	{
		define('MOD_SKILL496_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[496] = '手熟';
	}
	
	function acquire496(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost496(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked496(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calculate_attack_weapon_skill_gain_base(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(496,$pa)) {
			$ret += 1;
		}
		return $ret;
	}
}

?>