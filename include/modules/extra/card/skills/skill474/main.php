<?php

namespace skill474
{
	function init() 
	{
		define('MOD_SKILL474_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[474] = '厌食';
	}
	
	function acquire474(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost474(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked474(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_edible_hpup(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(474,$pa)) return round($chprocess($theitem)*0.7);
		return $chprocess($theitem);
	}
	
	function get_edible_spup(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(474,$pa)) return round($chprocess($theitem)*0.7);
		return $chprocess($theitem);
	}
	
	/*function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if (\skillbase\skill_query(474,$pd))
		{
			eval(import_module('sys','logger'));
			$log.='<span class="yellow b">'.$pa['name'].'被传染上了技能「厌食」！</span><br>';
			\skillbase\skill_acquire(474,$pa);
			\skillbase\skill_lost(474,$pd);
		}
		return $chprocess($pa,$pd);	
	}*/
	
	/*function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(474,$pa) && $pa['user_commanded']==1 && $active && !$pa['is_counter'] && $pd['type']==0)
		{
			eval(import_module('sys','logger'));
			$log.='<span class="yellow b">你将技能「厌食」传染给了敌人！</span><br>';
			\skillbase\skill_lost(474,$pa);
			\skillbase\skill_acquire(474,$pd);
		}
		return $chprocess($pa,$pd,$active);	
	}*/
		
}

?>