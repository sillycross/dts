<?php

namespace skill476
{
	
	function init() 
	{
		define('MOD_SKILL476_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[476] = '尊严';
	}
	
	function acquire476(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost476(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked476(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ((\skillbase\skill_query(476,$pa))&&(check_unlocked476($pa))&&(strpos($pa['wep'],"精工")===false))
		{
			eval(import_module('logger'));
			if ($active)
				$log.="<span class=\"yellow\">「尊严」使你造成的最终伤害降低了40%！</span><br>";
			else  $log.="<span class=\"yellow\">「尊严」使敌人造成的最终伤害降低了40%！</span><br>";
			$r=Array(0.6);	
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
}

?>
