<?php

namespace skill459
{
	function init() 
	{
		define('MOD_SKILL459_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[459] = '菁英';
	}
	
	function acquire459(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost459(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked459(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//加成值
	function calculate_attack_exp_gain_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(459,$pa)) $ret *= 3;
		return $ret;
	}
	
	//修正值
	function calculate_attack_exp_gain_change(&$pa, &$pd, $active, $upexp)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active,$upexp);
		if (\skillbase\skill_query(459,$pd)) $ret = 0;
		return $ret;
	}
}

?>