<?php

namespace skill37
{
	function init() 
	{
		define('MOD_SKILL37_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[37] = '灭气';
	}
	
	function acquire37(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost37(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked37(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=6;
	}
	
	//怒气收获增加
	function calculate_attack_rage_gain_base(&$pa, &$pd, $active, $fixed_val=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $fixed_val);
		if (!\skillbase\skill_query(37,$pd) || !check_unlocked37($pd)) return $ret;
		return $ret + rand(1,2);
	}
	
	//打体力
	function attack_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(37,$pa) || !check_unlocked37($pa)) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (\weapon\get_skillkind($pa,$pd,$active) == 'wp' && !$pd['type'])	//只对玩家有效
		{
			$pd['sp']=max( $pd['sp']-round($pa['dmg_dealt']*2/3), 1 );
		}
		$chprocess($pa, $pd, $active);
	}
}

?>
