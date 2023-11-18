<?php

namespace skill703
{
	$skill703_flag = 0;
	
	function init() 
	{
		define('MOD_SKILL703_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[703] = '静寂';
	}
	
	function acquire703(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost703(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked703(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function battle_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(703,$pa) && check_unlocked703($pa)) || (\skillbase\skill_query(703,$pd) && check_unlocked703($pd)))
		{
			eval(import_module('skill703'));
			$skill703_flag = 1;
		}
		$chprocess($pa, $pd, $active);
	}
	
	function addnoise($where, $typ, $pid1 = -1, $pid2 = -1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill703'));
		if (!empty($skill703_flag)) return;
		$chprocess($where, $typ, $pid1, $pid2);
	}
	
}

?>