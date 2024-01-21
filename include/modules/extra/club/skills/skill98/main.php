<?php

namespace skill98
{
	function init()
	{
		define('MOD_SKILL98_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[98] = '集萃';
		$clubdesc_h[26] = '合成补给品耐久翻倍，';
	}
	
	function acquire98(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost98(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked98(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		skill98_check();
		$chprocess();
	}
	
	function recipe_mix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		skill98_check();
		$chprocess();
	}
	
	function skill98_check()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if (\skillbase\skill_query(98,$sdata) && check_unlocked98($sdata)) 
		{
			if (((strpos($itmk0,'H') === 0) || (strpos($itmk0,'P') === 0)) && !empty($itms0) && ($itms0 !== $nosta))
			{
				eval(import_module('logger'));
				$itms0 = ceil($itms0 * 2);
				$log .= "<span class=\"yellow b\">你制作的补给品数量增加了！</span><br>";
			}
		}
	}
	
}

?>