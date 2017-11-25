<?php

namespace skill256
{
	function init() 
	{
		define('MOD_SKILL256_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[256] = '拳法';
		$clubdesc_h[19] = $clubdesc_a[19] = '开局获得50点殴系熟练度';
	}
	
	function acquire256(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['wp']+=50;
	}
	
	function lost256(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked256(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
}

?>