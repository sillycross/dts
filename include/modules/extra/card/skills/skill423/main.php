<?php

namespace skill423
{
	function init() 
	{
		define('MOD_SKILL423_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[423] = '魔鬼';
	}
	
	function acquire423(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(423,'lvl','0',$pa);
	}
	
	function lost423(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(423,'lvl',$pa);
	}
	
	function check_unlocked423(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function apply_total_damage_modifier_invincible(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(423,$pd) || !check_unlocked423($pd)) return $chprocess($pa,$pd,$active);
		eval(import_module('logger'));
		if ($pa['type']==88){
			$pa['dmg_dealt']=0;
			if ($active) $log .= "<span class=\"yellow b\">你的攻击被敌人完全化解了！</span><br>";
			else $log .= "<span class=\"yellow b\">不知为什么，敌人穷凶极恶的攻击只是轻轻地落在了你的身后。</span><br>";
		}
		$chprocess($pa,$pd,$active);
	}
	
	//面对scp时，如果场上玩家数大于等于2，玩家攻击获得经验恒为1。
	function calculate_attack_exp_gain_change(&$pa, &$pd, $active, $expup)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active,$expup);
		eval(import_module('sys'));
		if ($alivenum > 1 && \skillbase\skill_query(423,$pa) && check_unlocked423($pa) && $pd['type']==88 && $ret > 0){
			$ret = 1;
		}
		return $ret;
	}
}

?>