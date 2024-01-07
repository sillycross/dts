<?php

namespace instance0
{
	function init() {}
	
	function checkcombo($time){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','gameflow_combo'));
		if(10 == $gametype || 14 == $gametype){//如果不是开局连斗，那么2禁或者死亡数160以上才会判定连斗。组队模式也放这里判定吧。
			if($areanum < $areaadd * 2 && $alivenum > 1 && $deathnum < $combonum){
				return;
			}
		}
		$chprocess($time);
	}
}

?>