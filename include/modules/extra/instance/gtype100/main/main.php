<?php

namespace gtype100
{
	function init() {}
	
	function checkendgame()	//跳过游戏结束判定
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($gametype==100) return;	
		$chprocess();
	}
	
	function checkcombo($time)	//不会连斗
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($gametype==100) return;	
		$chprocess($time);
	}
	
}

?>