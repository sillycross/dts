<?php

namespace skill705
{
	function init() 
	{
		define('MOD_SKILL705_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[705] = '制御';
	}
	
	function acquire705(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost705(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked705(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(705,$pa) && check_unlocked705($pa)) array_push($ret,'H');
		return $ret;
	}
	
}

?>