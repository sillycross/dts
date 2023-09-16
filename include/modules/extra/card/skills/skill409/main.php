<?php

namespace skill409
{
	
	function init() 
	{
		define('MOD_SKILL409_INFO','card;unique;');
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
				$log.="<span class=\"yellow b\">「不动」使敌人受到的最终伤害降低了10%！</span><br>";
			else  $log.="<span class=\"yellow b\">「不动」使你受到的最终伤害降低了10%！</span><br>";
			$r=Array(0.9);	
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function get_trap_damage_multiplier(&$pa, &$pd, $trap, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(409,$pd)) 
		{
			eval(import_module('logger'));
			$log.='「不动」使你受到的陷阱伤害降低了10%！<br>';
			$r=Array(0.9);
		}
		return array_merge($r,$chprocess($pa,$pd,$trap,$damage));
	}
}

?>
