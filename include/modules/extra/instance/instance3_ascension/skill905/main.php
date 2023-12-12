<?php

namespace skill905
{
	function init() 
	{
		define('MOD_SKILL905_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[905] = '蔽目';
	}
	
	function acquire905(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(905,'lvl','0',$pa);
	}
	
	function lost905(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked905(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function searchmemory_available()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(905, $sdata) && ((int)\skillbase\skill_getvalue(905, 'lvl', $sdata) >= 3)) return false;
		return $chprocess();
	}
	
	function calc_memory_slotnum(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = & $sdata;
		}
		$ret = $chprocess($pa);
		if (\skillbase\skill_query(905, $pa) && ((int)\skillbase\skill_getvalue(905, 'lvl', $pa) >= 2)) return max($ret-4,1);
		return $ret;
	}
	
	function calc_memory_recordnum(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = & $sdata;
		}
		$ret = $chprocess($pa);
		if (\skillbase\skill_query(905, $pa) && ((int)\skillbase\skill_getvalue(905, 'lvl', $pa) >= 1)) return max($ret-20,1);
		return $ret;
	}

}

?>