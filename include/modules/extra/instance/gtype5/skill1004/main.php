<?php

namespace skill1004
{
	function init() 
	{
		define('MOD_SKILL1004_INFO','unique;');
		eval(import_module('clubbase'));
		$clubskillname[1004] = '难度';
	}
	
	function acquire1004(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(1004,'lvl',0,$pa);//默认0表示不做限制
	}
	
	function lost1004(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked1004(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_skill1004_lvl(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($pa == NULL) {
			eval(import_module('player'));
			$pa = &$sdata;
		}
		if(!\skillbase\skill_query(1004, $pa)) return 0; //无此技能返回0
		return \skillbase\skill_getvalue(1004,'lvl',$pa);
	}
}

?>