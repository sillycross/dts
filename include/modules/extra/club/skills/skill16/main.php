<?php

namespace skill16
{
	function init() 
	{
		define('MOD_SKILL16_INFO','club;feature;');
		eval(import_module('clubbase'));
		$clubskillname[16] = '投手';
		$clubdesc_h[4] = $clubdesc_a[4] = '开局获得25点投系熟练度，每次升级时获得4-6点投系熟练度';
	}
	
	function acquire16(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['wc']+=25;
	}
	
	function lost16(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked16(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(16,$pa)) $pa['wc']+=rand(4,6);
		$chprocess($pa);
	}
}

?>
