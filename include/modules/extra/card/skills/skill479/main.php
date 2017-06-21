<?php

namespace skill479
{
	function init() 
	{
		define('MOD_SKILL479_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[479] = '口胡';
	}
	
	function acquire479(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost479(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked479(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//战斗伤害、陷阱伤害、事件伤害进行四舍五入
//	function strike_finish(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('logger'));
//		if (\skillbase\skill_query(479, $pd)) {
//			$pa['dmg_dealt'] = dmg_round479($pa['dmg_dealt'], $active);
//		}
//		$chprocess($pa, $pd, $active);
//	}
	
	//战斗伤害、陷阱伤害进行四舍五入，事件因为太乱了先不考虑
	function apply_total_damage_change(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(479, $pd)) {
			$pa['dmg_dealt'] = dmg_round479($pa['dmg_dealt'], $active);
		}
		$chprocess($pa, $pd, $active);//伤害制御最后计算
	}
	
	function get_trap_final_damage_change(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(479, $pd)) {
			$damage = dmg_round479($damage, 0);
		}
		return $damage;
	}
	
	function dmg_round479($dmg, $active) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$r_dmg = round($dmg/100)*100;
		if($r_dmg != $dmg) {
			if ($active) $log.="「口胡」使敌人受到的伤害由{$dmg}点变成了<span class=\"yellow\">{$r_dmg}</span>点！<br>";
			else $log.="「口胡」使你受到的伤害由{$dmg}点变成了<span class=\"yellow\">{$r_dmg}</span>点！<br>";
		}
		return $r_dmg;
	}
}

?>