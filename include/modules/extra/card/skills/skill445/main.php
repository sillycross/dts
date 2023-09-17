<?php

namespace skill445
{
	
	function init() 
	{
		define('MOD_SKILL445_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[445] = '转化';
	}
	
	function acquire445(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost445(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked445(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_internal_def(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(445,$pd))&&(check_unlocked445($pd))) return $chprocess($pa,$pd,$active)*1.7;
		return $chprocess($pa,$pd,$active);
	}
	
	function get_internal_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(445,$pa))&&(check_unlocked445($pa))) return $chprocess($pa,$pd,$active)*0.5;
		return $chprocess($pa,$pd,$active);
	}
}

?>