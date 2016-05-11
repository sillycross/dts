<?php

namespace skill249
{
	function init() 
	{
		define('MOD_SKILL249_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[249] = '雷击';
	}
	
	function acquire249(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost249(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked249(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_trap_damage_multiplier(&$pa, &$pd, $trap, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(249,$pa)) 
		{
			eval(import_module('logger'));
			$log.='对方精心埋设的陷阱使伤害增加了<span class="yellow">15</span>%！<br>';
			$r=Array(1.15);
		}
		return array_merge($r,$chprocess($pa,$pd,$trap,$damage));
	}
}

?>
