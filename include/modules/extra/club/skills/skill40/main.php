<?php

namespace skill40
{
	function init() 
	{
		define('MOD_SKILL40_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[40] = '活化';
	}
	
	function acquire40(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost40(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked40(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//基础攻击/防御增加
	function attack_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(40,$pa) && check_unlocked40($pa))
		{
			$pa['att']++;
		}
		if (\skillbase\skill_query(40,$pd) && check_unlocked40($pd))
		{
			$pd['def']++;
		}
		$chprocess($pa, $pd, $active);
	}
}

?>
