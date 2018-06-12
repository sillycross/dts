<?php

namespace ending
{
	function init() {}
	
	function ending_by_shootings_available()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','ending'));
		if($ending_by_shootings && in_array($gametype, $ending_by_shootings_gametype)) return true;
		return false;
	}
	
	//结尾时生成一些判定用的临时变量
	function init_playerdata()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($gamestate <= 0 && ending_by_shootings_available()) 
		{
			//最后杀掉的玩家
			
		}
		$chprocess();
	}
}

?>