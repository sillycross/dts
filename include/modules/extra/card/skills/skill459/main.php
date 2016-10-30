<?php

namespace skill459
{
	function init() 
	{
		define('MOD_SKILL459_INFO','club;unique;locked;');
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
	
	function calculate_attack_exp_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(459,$pd)) return 0;
		if (\skillbase\skill_query(459,$pa)) return $chprocess($pa,$pd,$active)*3;
		return $chprocess($pa,$pd,$active);
	}
}

?>
