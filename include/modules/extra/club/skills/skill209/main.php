<?php

namespace skill209
{
	function init() 
	{
		define('MOD_SKILL209_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[209] = '舞钢';
	}
	
	function acquire209(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost209(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked209(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=15;
	}
	
	function get_weapon_fluc_percentage(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ( \skillbase\skill_query(209,$pa) && check_unlocked209($pa) && \weapon\get_skillkind($pa,$pd,$active) == 'wk')
			return abs($chprocess($pa, $pd, $active));
		else  return $chprocess($pa, $pd, $active);
	}
}

?>
