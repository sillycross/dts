<?php

namespace skill231
{
	function init() 
	{
		define('MOD_SKILL231_INFO','club;hidden;');
		eval(import_module('clubbase'));
		$clubdesc_a[7] .= '<br>电击属性致伤率+20%';
		$clubdesc_h[7] .= '<br>电击属性致伤率+20%';
	}
	
	function acquire231(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost231(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked231(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function get_ex_inf_rate_modifier(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (($key=='e')&&(\skillbase\skill_query(231,$pa))) return ($chprocess($pa,$pd,$active,$key)+20);
		return $chprocess($pa,$pd,$active,$key);
	}
	
}

?>