<?php

namespace skill906
{
	function init() 
	{
		define('MOD_SKILL906_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[906] = '蚀骨';
	}
	
	function acquire906(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(906,'lvl','0',$pa);
	}
	
	function lost906(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked906(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_inf($hurtposition, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa == NULL) 
 		{
			eval(import_module('player'));
			$pa = &$sdata;
		}
		if (\skillbase\skill_query(906, $pa))
		{
			$skill906_lvl = (int)\skillbase\skill_getvalue(906, 'lvl', $pa);
			if ($skill906_lvl >= 1)
			{
				if (!isset($pa['sk906_flag']))
				{
					eval(import_module('logger'));
					$log .= "<span class=\"red b\">你感到了钻心的疼痛！</span><br>";
					$pa['sk906_flag'] = 1;
				}
				eval(import_module('sys'));
				\skillbase\skill_acquire(600,$pa);
				\skillbase\skill_setvalue(600,'start',$now,$pa);
				\skillbase\skill_setvalue(600,'end',$now+20,$pa);
			}
			if ($skill906_lvl >= 2)
			{
				$pa['mhp'] -= 10;
				if ($pa['mhp'] <= 0) $pa['mhp'] = 1;
				if ($pa['hp'] > $pa['mhp'] + 10) $pa['hp'] -= 10;
				else $pa['hp'] = min($pa['hp'], $pa['mhp']);
			}
		}
		$chprocess($hurtposition, $pa);
	}

}

?>