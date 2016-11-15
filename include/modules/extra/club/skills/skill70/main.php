<?php

namespace skill70
{
	function init() 
	{
		define('MOD_SKILL70_INFO','club;hidden;');
	}
	
	function acquire70(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['skillpoint']+=5;
		eval(import_module('weapon'));
		foreach (array_unique(array_values($skillinfo)) as $key)
			$pa[$key]+=5;
	}
	
	function lost70(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked70(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		eval(import_module('lvlctl'));
		if (\skillbase\skill_query(70,$pa) && $pa['lvl']%2==0)
			$lvupskpt ++;
	}
	
	function get_skill_by_kind(&$pa, &$pd, $active, $wep_kind)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(70,$pa)) return $chprocess($pa,$pd,$active,$wep_kind);
		eval(import_module('weapon'));
		$fsk=$chprocess($pa,$pd,$active,$wep_kind);
		$r70=0.25;
		if ($pa['lvl']>=19) $r70=0.55;
		foreach (array_unique(array_values($skillinfo)) as $key)
			if ($key!=$wep_kind)
				$fsk+=$r70*$pa[$key];
		$fsk=round($fsk);
		return $fsk;
	}
	
}

?>
