<?php

namespace skill224
{
	function init() 
	{
		define('MOD_SKILL224_INFO','club;hidden;');
		eval(import_module('clubbase'));
		$clubdesc_a[8] .= '<br>带毒属性致伤率+12%';
		$clubdesc_h[8] .= '<br>带毒属性致伤率+12%';
	}
	
	function acquire224(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost224(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function get_ex_inf_rate_modifier(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (($key=='p')&&(\skillbase\skill_query(224,$pa))) return ($chprocess($pa,$pd,$active,$key)+12);
		return $chprocess($pa,$pd,$active,$key);
	}
	
}

?>
