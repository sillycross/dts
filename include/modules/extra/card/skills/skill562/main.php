<?php

namespace skill562
{
	function init() 
	{
		define('MOD_SKILL562_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[562] = '过劳';
	}
	
	function acquire562(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['state'] = 1;
		$pa['mode'] = 'rest';
		if (rand(0,99) < 30) $pa['money'] += rand(10,300);	
	}
	
	function lost562(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		if (get_var_in_module('mode','sys') == 'rest' && get_var_in_module('command','sys') == 'back' && \skillbase\skill_query(562))
		{
			eval(import_module('logger'));
			$log = "<span class=\"yellow b\">刚才好像做了个奇怪的梦……咦我这是在哪？</span><br><br>……<br><br>";
			\skillbase\skill_lost(562);
		}
		$chprocess();
	}
	
}

?>