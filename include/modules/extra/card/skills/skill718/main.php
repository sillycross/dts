<?php

namespace skill718
{
	function init() 
	{
		define('MOD_SKILL718_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[718] = '双面';
	}
	
	function acquire718(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//技能效果写在profile.htm里
		if (rand(0,1)) \skillbase\skill_lost(718,$pa);
	}
	
	function lost718(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function check_unlocked718(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function itemget()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$itm0_temp = $itm0;
		$chprocess();
		if (\skillbase\skill_query(718,$sdata) && $itm0_temp == '硬币')
		{
			eval(import_module('logger'));
			$log .= "<span class=\"yellow b\">脸朝下的你与它惺惺相惜。</span><br>";
		}
	}

}

?>