<?php

namespace skill579
{
	function init() 
	{
		define('MOD_SKILL579_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[579] = '睡拳';
	}
	
	function acquire579(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost579(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked579(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if ($mode == 'command' && ($command=='rest1' || $command=='rest2' || $command=='rest3'))
		{
			if (\skillbase\skill_query(579,$sdata) && check_unlocked579($sdata))
			{
				eval(import_module('logger'));
				$log .= "<span class=\"yellow b\">你立于原地，摆好架势！</span><br>";
				$pose = 2;
			}
		}
		
		$chprocess();
	}

	function rest($restcommand) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if (\skillbase\skill_query(579,$sdata) && check_unlocked579($sdata))
		{
			if (($restcommand != 'rest') && ($pose == 2)) $pose = 1;
		}
		
		$chprocess($restcommand);
	}
	
}

?>