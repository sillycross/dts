<?php

namespace skill432
{
	
	function init() 
	{
		define('MOD_SKILL432_INFO','card;unique;locked;');
		eval(import_module('clubbase'));
		$clubskillname[432] = '冰心';
	}
	
	function acquire432(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost432(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function check_unlocked432(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calculate_hp_rev_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(432,$pa))&&(check_unlocked432($pa))){
			return 0;
		}
		return $chprocess($pa,$pd,$active);
	}

}

?>
