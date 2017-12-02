<?php

namespace skill260
{
	function init() 
	{
		define('MOD_SKILL260_INFO','club;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[260] = '熟练';
		$clubdesc_a[19] .= '<br>空手作战时有35%/5%/5%/5%的几率额外获得1/2/3/4点熟练';
		$clubdesc_h[19] .= '<br>空手作战时有35%/5%/5%/5%的几率额外获得1/2/3/4点熟练';
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
		return 1;
		//return $pa['lvl']>=2;
	}
	
	function calculate_attack_weapon_skill_gain_base(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r260=0;
		if ((\skillbase\skill_query(260,$pa))&&(check_unlocked260($pa))&&($pa['wepk']=="WN")) {
			$t260=rand(0,99);
			if ($t260<50) $r260++;
			if ($t260<15) $r260++;
			if ($t260<10) $r260++;
			if ($t260<5) $r260++;
		}
		return $r260+$chprocess($pa,$pd,$active);
	}
}

?>
