<?php

namespace skill454
{

	function init() 
	{
		define('MOD_SKILL454_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[454] = '团购';
	}
	
	function acquire454(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost454(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked454(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calculate_shop_itembuy_cost($price,$bnum)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(454)) return $chprocess($price,$bnum);
		//不与富家子弟效果叠加
		if (\skillbase\skill_query(69)) return $chprocess($price,$bnum);
		if ($bnum<=1) return $chprocess($price,$bnum);
		return round($chprocess($price,$bnum)*0.75);
	}
}

?>
