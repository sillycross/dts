<?php

namespace skill741
{
	function init()
	{
		define('MOD_SKILL741_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[741] = '机变';
	}
	
	function acquire741(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost741(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked741(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_rapid_accuracy_loss(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(741, $pa) && check_unlocked741($pa))
		{
			$ret = 1;
		}
		return $ret;
	}
	
}

?>