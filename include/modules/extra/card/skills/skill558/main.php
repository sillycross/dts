<?php

namespace skill558
{
	$skill558_keyword = '挑战者';
	
	function init() 
	{
		define('MOD_SKILL558_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[558] = '挑战';
	}
	
	function acquire558(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost558(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked558(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//如果自己卡片名有挑战者，受到的反噬伤害增加
	function calculate_hp_rev_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		if ((\skillbase\skill_query(558,$pa))&&(check_unlocked558($pa))){
			eval(import_module('skill558'));
			if (strpos($pa['cardname'], $skill558_keyword) !== false) {
				eval(import_module('logger'));
//				if ($active) $log.="<span class=\"yellow b\">你的「挑战」使你受到的反噬伤害增加了30%！</span><br>";
//				else $log.="<span class=\"yellow b\">敌人的「挑战」使其受到的反噬伤害增加了30%！</span><br>";
				return min(round(1.3 * $chprocess($pa,$pd,$active)), $pa['hp'] - 1);
			}
		}
		return $chprocess($pa,$pd,$active);
	}

	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = Array();
		if ((\skillbase\skill_query(558,$pa))&&(check_unlocked558($pa)))
		{
			eval(import_module('skill558'));
			if (strpos($pd['cardname'], $skill558_keyword) !== false) {
				eval(import_module('logger'));
				if ($active) $log.="<span class=\"yellow b\">「挑战」使你造成的最终伤害增加了30%！</span><br>";
				else $log.="<span class=\"yellow b\">「挑战」使敌人造成的最终伤害增加了30%！</span><br>";
				$r = Array(1.3);
			}
		}
		return array_merge($r, $chprocess($pa,$pd,$active));
	}
}

?>