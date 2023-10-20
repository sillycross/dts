<?php

namespace skill584
{
	function init() 
	{
		define('MOD_SKILL584_INFO','club;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[584] = '馒头';
	}
	
	function acquire584(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost584(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function enter_battlefield_cardproc($ebp, $card)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		$ret = $chprocess($ebp, $card);
		if ($card == 344) return skill584_add_flag($ret);
		else return $ret;
	}
	
	function skill584_add_flag($proc)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$proc[1][584] = '0';
		return $proc;
	}

	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		$chprocess($pa);
		eval(import_module('sys','player'));
		if (\skillbase\skill_query(584, $pa))
		{
			$pa['club'] = 17;
			$arr = \skillbase\get_acquired_skill_array($pa);
			foreach ($arr as $key)
			{
				if (defined('MOD_SKILL'.$key.'_INFO') && (strpos(constant('MOD_SKILL'.$key.'_INFO'),'club;')!==false || strpos(constant('MOD_SKILL'.$key.'_INFO'),'card;')!==false))
				{
					\skillbase\skill_lost($key,$pa);
				}
				\skillbase\skill_acquire(12,$pa);
			}
		}
	}
	
}

?>