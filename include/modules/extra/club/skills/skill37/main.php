<?php

namespace skill37
{
	function init() 
	{
		define('MOD_SKILL37_INFO','club;');
	}
	
	function acquire37(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost37(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
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
	
	function check_unlocked37(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=6;
	}
	
	//怒气收获增加
	function calculate_attack_rage_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(37,$pd) || !check_unlocked37($pd)) return $chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)+rand(1,2);
	}
	
	//打体力
	function attack_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(37,$pa) || !check_unlocked37($pa)) return $chprocess($pa, $pd, $active);
		if ($pa['wepk']=='WP' && !$pd['type'])	//只对玩家有效
		{
			$pd['sp']=max( $pd['sp']-round($pa['dmg_dealt']*2/3), 1 );
		}
		$chprocess($pa, $pd, $active);
	}
}

?>
