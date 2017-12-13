<?php

namespace skill15
{
	function init() 
	{
		define('MOD_SKILL15_INFO','club;feature;');
		eval(import_module('clubbase'));
		$clubskillname[15] = '射手';
		$clubdesc_h[3] = $clubdesc_a[3] = '开局获得25点射系熟练度，每次升级时获得4-6点射系熟练度';
	}
	
	function acquire15(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['wg']+=25;
	}
	
	function lost15(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked15(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(15,$pa)) $pa['wg']+=rand(4,6);
		$chprocess($pa);
	}
}

?>
