<?php

namespace skill233
{
	function init() 
	{
		define('MOD_SKILL233_INFO','club;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[233] = '网瘾';
		$clubdesc_a[7] .= '<br>使用移动PC解除禁区成功率为95%，且完全无风险';
	}
	
	function acquire233(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost233(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function calculate_hack_proc_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('item_uee'));
		if (\skillbase\skill_query(233)) return 95;
		return $chprocess();
	}
	
	function post_hack_events($itmn = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if (\skillbase\skill_query(233)) return;
		$chprocess($itmn);
	}
}

?>
