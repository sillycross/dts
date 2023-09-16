<?php

namespace skill479
{
	function init() 
	{
		define('MOD_SKILL479_INFO','card;');
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

	//特殊变化次序注册
	function apply_total_damage_modifier_special_set_sequence(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(479, $pd) && check_unlocked479($pd)) 
			$pd['atdms_sequence'][200] = 'skill479';
		return;
	}
	
	//特殊变化生效判定，建议采用或的逻辑关系
	function apply_total_damage_modifier_special_check(&$pa, &$pd, $active, $akey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $akey);
		if('skill479' == $akey && $pa['dmg_dealt'] > 0) $ret = 1;
		return $ret;
	}
	
	//特殊变化执行
	function apply_total_damage_modifier_special_core(&$pa, &$pd, $active, $akey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd, $active, $akey);
		if('skill479' == $akey){
			$pa['dmg_dealt'] = dmg_round479($pa, $pd, $active, $pa['dmg_dealt']);
		}
	}
	
	function get_trap_final_damage_change(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(479, $pd)) {
			$damage = dmg_round479($pa, $pd, 0, $damage);
		}
		return $damage;
	}
	
	function dmg_round479(&$pa, &$pd, $active, $dmg) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$r_dmg = round($dmg/100)*100;
		if($r_dmg != $dmg) {
			$log .= \battle\battlelog_parser($pa, $pd, $active, "「口胡」使<:pd_name:>受到的伤害由{$dmg}点变成了<span class=\"yellow b\">{$r_dmg}</span>点！<br>");
//			if ($active) $log.="「口胡」使敌人受到的伤害由{$dmg}点变成了<span class=\"yellow b\">{$r_dmg}</span>点！<br>";
//			else $log.="「口胡」使你受到的伤害由{$dmg}点变成了<span class=\"yellow b\">{$r_dmg}</span>点！<br>";
		}
		return $r_dmg;
	}
}

?>