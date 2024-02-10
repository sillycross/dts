<?php

namespace skill31
{
	function init() 
	{
		define('MOD_SKILL31_INFO','club;locked;feature;');
		eval(import_module('clubbase'));
		$clubskillname[31] = '根性';
		$clubdesc_a[13] .= '<br>食用补给HP恢复效果变为250%';//根性的特性显示实际上用的是这个技能
		$clubdesc_h[13] .= '<br>食用补给HP恢复效果变为250%';
	}
	
	function acquire31(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost31(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked31(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_edible_hpup(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(31,$pa)) return round($chprocess($theitem)*2.5);
		return $chprocess($theitem);
	}
}

?>
