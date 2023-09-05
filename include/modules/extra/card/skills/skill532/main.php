<?php

namespace skill532
{
	function init() 
	{
		define('MOD_SKILL532_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[532] = '无毁';
	}
	
	function acquire532(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost532(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked532(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$pa) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		$dummy = \player\create_dummy_playerdata();
		return ($pa['wepe'] > 10000 && 'wp' == \weapon\get_skillkind($pa,$dummy,1));
	}
	
	//钝器效果值大于10000时，武器损耗率变成0
	function calculate_wepimp_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(532, $pa) && check_unlocked532($pa)) $ret = 0;
		return $ret;
	}
}
?>