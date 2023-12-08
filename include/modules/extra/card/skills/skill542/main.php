<?php

namespace skill542
{
	$skill542_slotnum = 2;
	
	function init() 
	{
		define('MOD_SKILL542_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[542] = '千里';
	}
	
	function acquire542(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(542,'lvl',3,$pa);//默认增加3格
	}
	
	function lost542(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked542(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calc_memory_slotnum(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = & get_var_in_module('sdata', 'player');
		}	
		
		$ret = $chprocess($pa);
		if (\skillbase\skill_query(542,$pa)) {
			$ret += \skillbase\skill_getvalue(542,'lvl',$pa);
		}
		return $ret;
	}
}

?>