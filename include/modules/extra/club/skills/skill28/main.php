<?php

namespace skill28
{
	function init() 
	{
		define('MOD_SKILL28_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[28] = '毅重';
	}
	
	function acquire28(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost28(&$pa)
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
	
	function check_unlocked28(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=3;
	}
	
	function get_ex_def_array(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(28, $pd) || !check_unlocked28($pd)) return $chprocess($pa, $pd, $active);
		$r = $chprocess($pa, $pd, $active);
		if (\itemmain\count_itmsk_num($pd['wepsk'])===0) array_push($r, 'A');
		return $r;
	}
		
}

?>
