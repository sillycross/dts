<?php

namespace skill18
{
	function init() 
	{
		define('MOD_SKILL18_INFO','club;feature;');
		eval(import_module('clubbase'));
		$clubskillname[18] = '超能';
		$clubdesc_h[9] = $clubdesc_a[9] = '开局获得20点灵系熟练度，每次升级时获得3-5点灵系熟练度';
	}
	
	function acquire18(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['wf']+=20;
	}
	
	function lost18(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked18(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(18,$pa)) $pa['wf']+=rand(3,5);
		$chprocess($pa);
	}
}

?>
