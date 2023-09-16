<?php

namespace skill443
{
	function init() 
	{
		define('MOD_SKILL443_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[443] = '决斗';
	}
	
	function acquire443(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost443(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked443(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(443,$pa))&&(check_unlocked443($pa)))
		{
			eval(import_module('logger'));
			$log.='<span class="yellow b">「决斗」使你的基础攻击力提升了10点！<br></span>';
			$pa['att']+=10;
		}
		$chprocess($pa, $pd, $active);
	}
}

?>
