<?php

namespace skill231
{
	function init() 
	{
		define('MOD_SKILL231_INFO','club;hidden;');
	}
	
	function acquire231(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost231(&$pa)
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
		if (($key=='e')&&(\skillbase\skill_query(231,$pa))) return ($chprocess($pa,$pd,$active,$key)+20);
		return $chprocess($pa,$pd,$active,$key);
	}
	
}

?>
