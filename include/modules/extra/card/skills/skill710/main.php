<?php

namespace skill710
{
	function init() 
	{
		define('MOD_SKILL710_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[710] = '解限';
	}
	
	function acquire710(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost710(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked710(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//吃熟练书无衰减
	function calc_skillbook_efct($itme, $skcnt, $ws_sum)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(710)) return $itme;
		else return $chprocess($itme, $skcnt, $ws_sum);
	}
	
	//吃熟练药无衰减
	function calc_skillmed_efct($itme, $skcnt, $ws_sum)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(710)) return $itme;
		else return $chprocess($itme, $skcnt, $ws_sum);
	}
	
}

?>