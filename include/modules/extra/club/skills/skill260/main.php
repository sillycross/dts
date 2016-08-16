<?php

namespace skill260
{
	function init() 
	{
		define('MOD_SKILL260_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[260] = '熟练';
	}
	
	function acquire260(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost260(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked260(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=2;
	}
	
	function calculate_attack_weapon_skill_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r260=0;
		if ((\skillbase\skill_query(260,$pa))&&(check_unlocked260($pa))&&($pa['wepk']=="WN")) {
			$t260=rand(0,99);
			if ($t260<30) $r260++;
			if ($t260<15) $r260++;
			if ($t260<10) $r260++;
			if ($t260<5) $r260++;
		}
		return $r260+$chprocess($pa,$pd,$active);
	}
}

?>
