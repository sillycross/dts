<?php

namespace skill407
{
	
	function init() 
	{
		define('MOD_SKILL407_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[407] = '红石';
	}
	
	function acquire407(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost407(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked407(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_def_base(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		$ret = $chprocess($pa,$pd,$active);
		$var_407=0;
		if (($pd['hp']>($pd['mhp']/2))&&(\skillbase\skill_query(407,$pd))&&(check_unlocked407($pd))) {
			$var_407=\weapon\get_att($pd,$pa,1-$active);
			if ($var_407>100000) $var_407=100000;
			$var_407 = round($var_407 / 2);
			$pd['def_words'] = \attack\add_format($var_407, $pd['def_words'],0);
			$ret += $var_407;
		}
		return $ret;
	}
	
	function get_att_base(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('armor'));
		$ret = $chprocess($pa,$pd,$active);
		$var_407=0;
		if (($pa['hp']<($pa['mhp']/2))&&(\skillbase\skill_query(407,$pa))&&(check_unlocked407($pa))) {
			$var_407=\weapon\get_def($pd,$pa,$active);
			if ($var_407>100000) $var_407=100000;
			$var_407 = round($var_407 / 2);
			$pa['att_words'] = \attack\add_format($var_407, $pa['att_words'],0);
			$ret += $var_407;
		}
		return $ret;
	}
}

?>