<?php

namespace skill80
{
	function init() 
	{
		define('MOD_SKILL80_INFO','club;feature;');
		eval(import_module('clubbase'));
		$clubskillname[80] = '脑力';
		//最强大脑的特性显示实际上用的是这个技能
	}
	
	function acquire80(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost80(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked80(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//探索记忆总数加倍
	function calc_memory_recordnum(&$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = & $sdata;
		}	
		$ret = $chprocess($pa);
		if(\skillbase\skill_query(80,$pa) && check_unlocked80($pa)) $ret *= 2;
		return $ret;
	}
}

?>