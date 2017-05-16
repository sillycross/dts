<?php

namespace skill413
{
	function init() 
	{
		define('MOD_SKILL413_INFO','card;hidden;');
	}
	
	function acquire413(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost413(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if(\skillbase\skill_query(413) && (strpos($itm0,'【KEY系') === 0)&&($itms0 !== $nosta))
			$itms0 = ceil($itms0*1.2); 
			
		$chprocess();
	}
		
}

?>
