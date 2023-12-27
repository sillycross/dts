<?php

namespace skill225
{
	function init() 
	{
		define('MOD_SKILL225_INFO','club;feature;');
		eval(import_module('clubbase'));
		$clubskillname[225] = '高速';
		$clubdesc_h[10] = $clubdesc_a[10] = '每次攻击有2/3概率额外获得1点熟练度，<br>2/3概率额外获得1点经验值';
	}
	
	function acquire225(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost225(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked225(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calculate_attack_weapon_skill_gain_base(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(225,$pa)) {
			$a = 0;
			if(rand(0,2) < 2) $a = 1;
			$ret += $a;
		}
		return $ret;
	}
	
	function calculate_attack_exp_gain_base(&$pa, &$pd, $active, $fixed_val=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active,$fixed_val);
		if (\skillbase\skill_query(225,$pa)) {
			if(rand(0,2) < 2) $ret += 1;
		}
		return $ret;
	}
	
}

?>