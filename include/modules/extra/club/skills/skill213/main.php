<?php

namespace skill213
{
	function init() 
	{
		define('MOD_SKILL213_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[213] = '反思';
	}
	
	function acquire213(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost213(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked213(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
		
	function apply_attack_exp_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa,$pd,$active);
		
		eval(import_module('lvlctl'));
		if ((!empty($pa['physical_dmg_dealt']) && $pa['physical_dmg_dealt'] <= 0)&&(\skillbase\skill_query(213,$pa))&&(check_unlocked213($pa))&&(\weapon\get_skillkind($pa,$pd,$active) == 'wd')) //如果没有伤害，则获得1点经验
			\lvlctl\getexp(\weapon\calculate_attack_exp_gain($pa, $pd, $active, 1), $pa);//这样可以享受经验加成
//			\lvlctl\getexp(1,$pa);
	}
}

?>