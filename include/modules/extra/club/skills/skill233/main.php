<?php

namespace skill233
{
	function init() 
	{
		define('MOD_SKILL233_INFO','club;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[233] = '网瘾';
	}
	
	function acquire233(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost233(&$pa)
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
	
	function calculate_hack_proc_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('item_uee'));
		if (\skillbase\skill_query(233)) return 95;
	}
	
	function post_hack_events($itmn = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if (\skillbase\skill_query(233)) return;
	}
}

?>
