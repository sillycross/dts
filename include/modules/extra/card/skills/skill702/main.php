<?php

namespace skill702
{
	$skill702_flag = 0;
	
	function init() 
	{
		define('MOD_SKILL702_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[702] = '宵暗';
	}
	
	function acquire702(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost702(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked702(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function meetman_alternative($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(702,$edata) && check_unlocked702($edata))
		{
			eval(import_module('skill702'));
			$skill702_flag = 1;
		}
		$chprocess($edata);
	}
	
	function init_battle($ismeet = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($ismeet);
		eval(import_module('player','metman','skill702'));
		//如果自己或对方有宵暗技能，则对方显示为???
		if ((\skillbase\skill_query(702, $sdata) && check_unlocked702($sdata)) || !empty($skill702_flag))
		{
			$tdata['sNoinfo'] = '？？？';
			//一团黑
			$tdata['iconImg'] = 'n_900.gif';
			$tdata['iconImgB'] = '';
			$tdata['name'] = '？？？';
			$tdata['wep'] = '？？？';
			$tdata['infdata'] = '？？？';
			$tdata['pose'] = '？？？';
			$tdata['tactic'] = '？？？';
			$tdata['lvl'] = '？';
			$tdata['hpstate'] = '？？？';
			$tdata['spstate'] = '？？？';
			$tdata['ragestate'] = '？？？';
			$tdata['wepestate'] = '？？？';
			$tdata['wepk'] = '？？？';
			$skill702_flag = 0;
		}
	}
	
}

?>