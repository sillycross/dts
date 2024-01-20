<?php

namespace skill93
{
	function init()
	{
		define('MOD_SKILL93_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[93] = '回响';
	}
	
	function acquire93(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost93(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked93(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=13;
	}
	
	//没有音波则获得音波
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(93, $pa) && check_unlocked93($pa) && ($pd['ss'] >= $pd['mss'] * 0.5))
		{
			if (in_array('w', $ret)) $pa['skill93_flag'] = 1;
			else array_push($ret,'w');
		}
		return $ret;
	}
	
	//有音波则音波伤害增加
	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $key);
		if (($key == 'w') && isset($pa['skill93_flag']))
		{
			eval(import_module('logger'));
			if ($active) $log.="<br><span class=\"yellow b\">「回响」使你造成的音波伤害大幅增加了！</span><br>";
			else $log.="<br><span class=\"yellow b\">「回响」使{$pa['name']}造成的音波伤害大幅增加了！</span><br>";
			$ret *= 1.6;
		}
		return $ret;
	}
	
	//获得激奏2
	function get_ex_def_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(93, $pd) && check_unlocked93($pd) && ($pd['ss'] <= $pd['mss'] * 0.5)) array_push($ret,'^sa2');
		return $ret;
	}
	
}

?>