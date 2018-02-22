<?php

namespace skill471
{
	function init() 
	{
		define('MOD_SKILL471_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[471] = '气功';
	}
	
	function acquire471(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost471(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked471(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function apply_total_damage_modifier_insurance(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(471,$pd) && $pa['dmg_dealt']>=$pd['hp'] && $pd['rage']>0)
		{
			$dmgred = min(round($pd['rage']*1.5), $pa['dmg_dealt']-$pd['hp']+1);
			$rageused = floor($dmgred/1.5);
			eval(import_module('logger'));
			if ($active)
				$log.="<span class=\"yellow\">敌人发动气功，消耗{$rageused}点怒气抵挡了<span class=\"red\">{$dmgred}</span>点伤害！</span><br>";
			else  $log.="<span class=\"yellow\">你发动气功，消耗{$rageused}点怒气抵挡了<span class=\"red\">{$dmgred}</span>点伤害！</span><br>";
			$pd['rage']-=$rageused;
			$pa['dmg_dealt']-=$dmgred;
		}
	}
}

?>