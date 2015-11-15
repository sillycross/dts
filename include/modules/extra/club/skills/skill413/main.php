<?php

namespace skill413
{
	function init() 
	{
		define('MOD_SKILL413_INFO','club;hidden;');
	}
	
	function acquire413(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost413(&$pa)
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
		if(\skillbase\skill_query(413) && (strpos($itm0,'【KEY系') === 0)&&($itms0 !== $nosta))
			$itms0 = ceil($itms0*1.3); 
			
		$chprocess();
	}
		
}

?>
