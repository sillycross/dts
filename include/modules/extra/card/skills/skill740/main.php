<?php

namespace skill740
{
	function init()
	{
		define('MOD_SKILL740_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[740] = '跳过';
	}
	
	function acquire740(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost740(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked740(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function opening_by_shootings_available()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess();
		if (\skillbase\skill_query(740)) $ret = false;
		return $ret;
	}
	
	function event_main()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$temp_log = $log;
		$ret = $chprocess();
		if (\skillbase\skill_query(740) && isset($temp_log))
		{
			$log = $temp_log;
			$log .= "你在事件还没反应过来之前就按下了跳过键。<br>";
		}
		return $ret;
	}
	
}

?>