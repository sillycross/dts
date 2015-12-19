<?php

namespace skill226
{
	function init() 
	{
		define('MOD_SKILL226_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[226] = '神智';
	}
	
	function acquire226(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost226(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked226(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=7;
	}
	
	function apply_attack_exp_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa,$pd,$active);
		//不命中没有经验
		eval(import_module('lvlctl'));
		if (($pa['physical_dmg_dealt']>0)&&(\skillbase\skill_query(226,$pa))&&(check_unlocked226($pa))) 
			\lvlctl\getexp(1,$pa);
	}
}

?>
