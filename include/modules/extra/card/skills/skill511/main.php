<?php

namespace skill511
{
	function init() 
	{
		define('MOD_SKILL511_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[511] = '嘴强';
	}
	
	function acquire511(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost511(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked511(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function post_damage_news(&$pa, &$pd, $active, $dmg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(511,$pa) && check_unlocked511($pa)) {
			$dmg *= rand(233, 998);
		}
		$chprocess($pa, $pd, $active, $dmg);
	}
}

?>