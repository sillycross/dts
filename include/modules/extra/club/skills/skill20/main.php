<?php

namespace skill20
{
	function init() 
	{
		define('MOD_SKILL20_INFO','club;hidden;');
	}
	
	function acquire20(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost20(&$pa)
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
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if(\skillbase\skill_query(20) && (strpos($itmk0,'WD') === 0)&&($itms0 !== $nosta))
			$itms0 = ceil($itms0*1.5); 
			
		$chprocess();
	}
		
}

?>
