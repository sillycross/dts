<?php

namespace skill409
{
	
	function init() 
	{
		define('MOD_SKILL409_INFO','club;unique;locked;');
		eval(import_module('clubbase'));
		$clubskillname[409] = '不动';
	}
	
	function acquire409(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost409(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function check_unlocked409(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ((\skillbase\skill_query(409,$pd))&&(check_unlocked409($pd)))
		{
			eval(import_module('logger'));
			if ($active)
				$log.="<span class=\"yellow\">「不动」使敌人受到的最终伤害降低了10%！</span><br>";
			else  $log.="<span class=\"yellow\">「不动」使你受到的最终伤害降低了10%！</span><br>";
			$r=Array(0.9);	
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function get_trap_damage()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ((\skillbase\skill_query(409))&&(check_unlocked409()))return 0.9*$chprocess();
		return $chprocess();
	}
}

?>
