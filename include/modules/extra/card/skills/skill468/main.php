<?php

namespace skill468
{
	function init() 
	{
		define('MOD_SKILL468_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[468] = '崎岖';
	}
	
	function acquire468(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost468(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked468(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function check_skill468_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (rand(0,99)<17 && \skillbase\skill_query(468,$pd) && $pa['is_hit'])
		{
			eval(import_module('player','logger'));
			if ($active)
				$log.="<span class=\"cyan b\">母山岭巨人的力量使你晕了过去！</span></span><br>";
			else  $log.="<span class=\"cyan b\">母山岭巨人的力量使敌人晕了过去！</span></span><br>";
			\skill602\set_stun_period(2500,$pa);
			\skill602\send_stun_battle_news($pd['name'],$pa['name']);
		}
		return Array();
	}
	
//	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		check_skill468_proc($pa,$pd,$active);
//		return $chprocess($pa,$pd,$active);
//	}
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		check_skill468_proc($pa,$pd,$active);
		$chprocess($pa,$pd,$active);
	}
}

?>