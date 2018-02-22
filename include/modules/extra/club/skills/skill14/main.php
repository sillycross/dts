<?php

namespace skill14
{
	function init() 
	{
		define('MOD_SKILL14_INFO','club;feature;');
		eval(import_module('clubbase'));
		$clubskillname[14] = '剑士';
		$clubdesc_h[2] = $clubdesc_a[2] = '开局获得25点斩系熟练度，每次升级时获得4-6点斩系熟练度';
	}
	
	function acquire14(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['wk']+=25;
	}
	
	function lost14(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked14(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(14,$pa)) $pa['wk']+=rand(4,6);
		$chprocess($pa);
	}
}

?>
