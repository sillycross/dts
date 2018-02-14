<?php

namespace skill233
{
	function init() 
	{
		define('MOD_SKILL233_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[233] = '网瘾';
		$clubdesc_h[7] .= '<br>使用移动PC解除禁区成功率为95%，且完全无风险';
	}
	
	function acquire233(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost233(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked233(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calculate_hack_proc_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('item_uee'));
		if (\skillbase\skill_query(233)) return 95;
		return $chprocess();
	}
	
//	function post_hack_events($itmn = 0)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','player'));
//		if (\skillbase\skill_query(233)) return;
//		$chprocess($itmn);
//	}
	
	function calculate_post_hack_proc_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		list($v1, $v2) = $chprocess();
		if (\skillbase\skill_query(233)) {
			$v1 = $v2 = 0;
		}
		return array($v1,$v2);
	}
}

?>
