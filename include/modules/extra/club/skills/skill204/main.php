<?php

namespace skill204
{
	function init() 
	{
		define('MOD_SKILL204_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[204] = '掠夺';
	}
	
	function acquire204(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost204(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked204(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=8;
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
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(204,$pa))&&(check_unlocked204($pa))&&($pd['type']>0))
		{
			$var_204=(min(25,$pa['lvl'])*5);
			$pa['money']+=$var_204;
		}
		$chprocess($pa, $pd, $active);
	}
}

?>
