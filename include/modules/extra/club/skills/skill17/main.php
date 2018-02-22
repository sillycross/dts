<?php

namespace skill17
{
	function init() 
	{
		define('MOD_SKILL17_INFO','club;feature;');
		eval(import_module('clubbase'));
		$clubskillname[17] = '拆弹';
		$clubdesc_h[5] = $clubdesc_a[5] = '开局获得25点爆系熟练度，每次升级时获得5-7点爆系熟练度';
	}
	
	function acquire17(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['wd']+=25;
	}
	
	function lost17(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked17(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(17,$pa)) $pa['wd']+=rand(5,7);
		$chprocess($pa);
	}
}

?>
