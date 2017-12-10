<?php

namespace skill13
{
	
	function init() 
	{
		define('MOD_SKILL13_INFO','club;feature;');
		eval(import_module('clubbase'));
		$clubskillname[13] = '斗士';
		$clubdesc_h[1] = $clubdesc_a[1] = '开局获得25点殴系熟练度，每次升级时获得4-6点殴系熟练度';
	}
	
	function acquire13(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['wp']+=25;
	}
	
	function lost13(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked13(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(13,$pa)) $pa['wp']+=rand(4,6);
		$chprocess($pa);
	}
}

?>
