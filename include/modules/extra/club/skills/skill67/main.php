<?php

namespace skill67
{
	function init() 
	{
		define('MOD_SKILL67_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[67] = '利息';
	}
	
	function acquire67(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost67(&$pa)
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
	
	function check_unlocked67(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=11;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(67,$pa) && check_unlocked67($pa))
		{
			$pa['money']+=($pa['club']==11?200:150);
		}
		$chprocess($pa);
	}
}

?>
