<?php

namespace skill598
{
	$skill598_flag = 0;
	
	function init() 
	{
		define('MOD_SKILL598_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[598] = '天王';
	}
	
	function acquire598(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost598(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked598(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function check_wep_ygo($wepk, $wepsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\itemmix_sync\itemmix_get_star($wepk) > 0) return true;
		//连接卡视作游戏王卡
		if (false !== \itemmain\check_in_itmsk('^l', $wepsk)) return true;
		//超量卡视作游戏王卡
		if (false !== \itemmain\check_in_itmsk('^xyz', $wepsk)) return true;
		return false;
	}
	
	function get_sec_attack_method(&$pdata, $orig=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(598, $pdata)) && check_unlocked598($pdata) && check_wep_ygo($pdata['wepk'], $pdata['wepsk']))
		{
			eval(import_module('skill598'));
			if (!empty($skill598_flag) && ($pdata['wepk'][1] != 'F')) return 'F';
		}
		return $chprocess($pdata, $orig);
	}
	
	function check_attack_method(&$pdata, $wm)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (($wm == 'F') && \skillbase\skill_query(598, $pdata) && check_unlocked598($pdata) && check_wep_ygo($pdata['wepk'], $pdata['wepsk'])) return 1;
		return $chprocess($pdata,$wm);
	}
	
	function meetman($sid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill598'));
		$skill598_flag = 1;
		$chprocess($sid);
	}

}

?>