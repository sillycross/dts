<?php

namespace skill481
{

	function init() 
	{
		define('MOD_SKILL481_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[481] = '■■';
	}
	
	function acquire481(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost481(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked481(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(481,$pa))
		{
			eval(import_module('logger'));
			if ($active)
				$log.='<span class="yellow">一股神秘的力量使敌人晕眩了1秒，你仿佛感觉获得了新的能量。</span><br>';
			else  $log.='<span class="yellow">一股神秘的力量使你晕眩了1秒，但你仿佛感觉刚刚完成了一件很有意义的事情。</span><br>';
			\skill602\set_stun_period(100,$pd);
		}
		return $chprocess($pa,$pd,$active);
	}
}

?>
