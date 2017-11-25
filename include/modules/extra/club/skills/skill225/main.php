<?php

namespace skill225
{
	function init() 
	{
		define('MOD_SKILL225_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[225] = '高速';
		$clubdesc_a[10] = '每次攻击额外获得1点熟练度';
	}
	
	function acquire225(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost225(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked225(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calculate_attack_weapon_skill_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(225,$pa)) return (1+$chprocess($pa,$pd,$active));
		return $chprocess($pa,$pd,$active);
	}
}

?>
