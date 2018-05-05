<?php

namespace skill513
{
	function init() 
	{
		define('MOD_SKILL513_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[513] = '心墙';
	}
	
	function acquire513(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost513(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked513(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function skill_enabled($skillid, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($skillid, $pa);
		if(!$ret && 513 != $skillid && !empty($pa['acquired_list'][513]) && empty($pa['npc_evolved'])) {
			$ret = true;//怕再次调用skill_query会有无限循环
			if(empty($pa['skill513_log'])) {
				eval(import_module('logger'));
				$log .= '<span class="red b">「心墙」使技能不会被无效化！</span><br>';
				$pa['skill513_log'] = 1;
			}
		}
		return $ret;
	}
}

?>