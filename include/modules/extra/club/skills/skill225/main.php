<?php

namespace skill225
{
	function init() 
	{
		define('MOD_SKILL225_INFO','club;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[225] = '高速';
	}
	
	function acquire225(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost225(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function calculate_attack_weapon_skill_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(225,$pa)) return (1+$chprocess($pa,$pd,$active));
		return $chprocess($pa,$pd,$active);
	}
}

?>
