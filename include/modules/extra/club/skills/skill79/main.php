<?php

namespace skill79
{
	function init() 
	{
		define('MOD_SKILL79_INFO','club;locked;feature;');
		eval(import_module('clubbase'));
		$clubskillname[79] = '肌肉';
		//肌肉的特性显示实际上用的是这个技能
	}
	
	function acquire79(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost79(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked79(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
}

?>