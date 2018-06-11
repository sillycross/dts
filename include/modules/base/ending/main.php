<?php

namespace ending
{
	function init() {}
	
	function get_storyboard_name()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($gamestate <= 0) return MOD_ENDING_STORYBOARD;
		return $chprocess();
	}
}

?>