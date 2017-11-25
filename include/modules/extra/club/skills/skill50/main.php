<?php

namespace skill50
{
	function init() 
	{
		define('MOD_SKILL50_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[50] = '枭眼';
	}
	
	function acquire50(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost50(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked50(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=9;
	}
	
	//注意，这里检查的是$pa（技能持有者）在面对$pd时，本技能是否符合发动条件
	function check_skill50_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r1 = \weapon\get_weapon_range($pa, $active);
		$r2 = \weapon\get_weapon_range($pd, 1-$active);
		if ($r1 >= $r2 && $r1 != 0 && $r2 != 0)
			return 1;
		else  return 0;
	}
	
	function get_hitrate_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(50,$pd) || !check_unlocked50($pd)) return $ret;
		if (!check_skill50_proc($pd, $pa, 1-$active)) return $ret;
		return $ret*0.88;
	}
	
	function get_rapid_accuracy_loss(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(50,$pd) || !check_unlocked50($pd)) return $chprocess($pa, $pd, $active);
		if (!check_skill50_proc($pd, $pa, 1-$active)) return $chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)*0.92;
	}
	
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(50,$ldata) && check_unlocked50($ldata) && check_skill50_proc($ldata, $edata, 1)) $r*=1.1;
		if (\skillbase\skill_query(50,$edata) && check_unlocked50($edata) && check_skill50_proc($edata, $ldata, 0)) $r/=1.1;
		return $chprocess($ldata,$edata)*$r;
	}
	
}

?>
