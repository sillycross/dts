<?php

namespace skill464
{
	function init() 
	{
		define('MOD_SKILL464_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[464] = '抖M';
	}
	
	function acquire464(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost464(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked464(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function strike_finish(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(464,$pd))
		{
			eval(import_module('sys','player','weapon','itemmain','logger'));
			foreach (array_unique(array_values($skillinfo)) as $key)
			{
				if ($$key>$maxv)
				{
					$maxv=$$key;
					$maxwhich=$key;
				}
			}
			$$maxwhich++;
			if (!$active) $log.='你的技能「抖M」使你的最高系熟练度增加了1！<br>';
		}
		return $chprocess($pa,$pd,$active);
	}
}

?>
