<?php

namespace skill736
{
	function init() 
	{
		define('MOD_SKILL736_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[736] = '匿迹';
	}
	
	function acquire736(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(736,'lvl',0,$pa);
	}
	
	function lost736(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(736,'lvl',$pa);
	}
	
	function check_unlocked736(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function deathnews(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(736,$pa) && check_unlocked736($pa))
		{
			$skill736_lvl = (int)\skillbase\skill_getvalue(736,'lvl',$pa);
			if ($skill736_lvl == 1)
			{
				eval(import_module('sys','map','player'));
				$lwname = $typeinfo [$pd['type']] . ' ' . $pa['name'];
				$lstwd = \player\get_player_lastword($pd);
				\sys\addchat(3, $lstwd, $lwname, '', 0, $pd['pls']);
				if (!empty($pd['sourceless'])) $x=''; else $x=$pa['name'];
				\sys\addnews ( $now, 'death' . $pd['state'], $pa['name'], $pd['type'], $x , $pa['attackwith'], $lstwd );
			}
			return;
		}
		$chprocess($pa, $pd);
	}
	
	function corpsedestroy_news(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(736,$pa) && check_unlocked736($pa))
		{
			$skill736_lvl = (int)\skillbase\skill_getvalue(736,'lvl',$pa);
			if ($skill736_lvl == 1) addnews ( 0, 'cdestroy', $pa['name'], $pa['name'] );
			return;
		}
		$chprocess($pa, $pd);
	}
	
	function post_damage_news(&$pa, &$pd, $active, $dmg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(736,$pa) && check_unlocked736($pa))
		{
			$skill736_lvl = (int)\skillbase\skill_getvalue(736,'lvl',$pa);
			if ($skill736_lvl != 1) return;
		}
		$chprocess($pa, $pd, $active, $dmg);
	}
	
}

?>