<?php

namespace skill257
{
	function init() 
	{
		define('MOD_SKILL257_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[257] = '仁义';
	}
	
	function acquire257(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost257(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked257(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_trap_damage_multiplier(&$pa, &$pd, $trap, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=1;
		if (\skillbase\skill_query(257,$pa)) 
		{
			eval(import_module('logger'));
			$log.='这个陷阱埋设的很拙劣，因而你受到的伤害减少了<span class="yellow">90</span>%！<br>';
			$r*=0.1;
		}
		if (\skillbase\skill_query(257,$pd)) 
		{
			eval(import_module('logger'));
			$log.='你灵活的反应使你受到的陷阱伤害减少了<span class="yellow">60</span>%！<br>';
			$r*=0.4;
		}
		if ($r!=1) $r=Array($r); else $r=array();
		return array_merge($r,$chprocess($pa,$pd,$trap,$damage));
	}
}

?>
