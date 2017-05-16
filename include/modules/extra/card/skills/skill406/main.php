<?php

namespace skill406
{
	
	function init() 
	{
		define('MOD_SKILL406_INFO','card;unique;locked;');
		eval(import_module('clubbase'));
		$clubskillname[406] = '心火';
	}
	
	function acquire406(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost406(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked406(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ((\skillbase\skill_query(406,$pa))&&(check_unlocked406($pa)))
		{
			eval(import_module('logger'));
			if ($active)
				$log.="<span class=\"yellow\">「心火」使你造成的最终伤害提高了100%！</span><br>";
			else  $log.="<span class=\"yellow\">「心火」使敌人造成的最终伤害提高了100%！</span><br>";
			$r=Array(2);	
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
}

?>
