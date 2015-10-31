<?php

namespace skill224
{
	function init() 
	{
		define('MOD_SKILL224_INFO','club;hidden;');
	}
	
	function acquire224(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost224(&$pa)
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
	
	function get_ex_inf_rate_modifier(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (($key=='p')&&(\skillbase\skill_query(224,$pa))) return ($chprocess($pa,$pd,$active,$key)+12);
		return $chprocess($pa,$pd,$active,$key);
	}
	
}

?>
