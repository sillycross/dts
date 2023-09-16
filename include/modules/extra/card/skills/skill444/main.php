<?php

namespace skill444
{
	
	function init() 
	{
		define('MOD_SKILL444_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[444] = '怒吼';
	}
	
	function acquire444(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost444(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked444(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ((\skillbase\skill_query(444,$pa))&&(check_unlocked444($pa)))
		{
			eval(import_module('logger'));
			if ($active)
				$log.="<span class=\"yellow b\">“就杀那个最菜的！”「怒吼」使你造成的最终伤害提高了100%！</span><br>";
			else  $log.="<span class=\"yellow b\">“就杀那个最菜的！”「怒吼」使敌人造成的最终伤害提高了100%！</span><br>";
			$r=Array(2);	
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function get_hitrate_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(444,$pa) || !check_unlocked444($pa)) return $ret;
		return $ret*0.45;
	}
	
	function get_rapid_accuracy_loss(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(444,$pa) || !check_unlocked444($pa)) return $chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)*0.9;
	}
}

?>
