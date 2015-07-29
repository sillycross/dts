<?php

namespace skill59
{
	function init() 
	{
		define('MOD_SKILL59_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[59] = '新生';
	}
	
	function acquire59(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost59(&$pa)
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
	
	function check_unlocked59(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return ((int)\skillbase\skill_getvalue(58,'r',$pa))==1;
	}
}

?>
