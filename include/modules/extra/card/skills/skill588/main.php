<?php

namespace skill588
{
	$sk588_wthkey_list = array(0,1,2,3,4,5,6,7,8,9,12,13,17);
	
	function init() 
	{
		define('MOD_SKILL588_INFO','card;active;');
		eval(import_module('clubbase'));
		$clubskillname[588] = '天变';	
	}
	
	function acquire588(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost588(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked588(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=12;
	}

	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		
		if (get_var_in_module('mode', 'sys') == 'special' && get_var_in_module('command', 'sys') == 'skill588_special' && get_var_in_module('subcmd', 'input') == 'castsk588') 
		{
			cast_skill588();
			return;
		}
		$chprocess();
	}

	function cast_skill588()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','weather','skill588'));
		if (!\skillbase\skill_query(588)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		if (\skillbase\skill_getvalue(588,'activated')) 
		{
			$log .= '你已经发动过「天变」了！<br>';
			return;
		}
		
		$sk588_wthkey_list = array_diff($sk588_wthkey_list, array($weather));
		$weather = $sk588_wthkey_list[array_rand($sk588_wthkey_list)];
		save_gameinfo ();
		
		$log .= "你发动技能「天变」，将天气转变成了<span class=\"red b\">$wthinfo[$weather]</span>！！<br>";
		\skillbase\skill_setvalue(588,'activated',1);
		addnews($now, 'wthchange', $name, $weather, '技能<span class=\"yellow b\">「天变」</span>');
		$mode = 'command';
		return;
	}
	
}

?>
