<?php

namespace skill487
{	
	function init() 
	{
		define('MOD_SKILL487_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[487] = '后手';
	}
	
	function acquire487(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(487,'lvl','0',$pa);
	}
	
	function lost487(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(487,'lvl',$pa);
	}
	
	function check_unlocked487(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
}

?>