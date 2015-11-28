<?php

namespace instance5
{
	function init() {}
	
	function checkcombo(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','gameflow_combo'));
		if (($gametype==15)&&($areanum<$areaadd*2)&&($alivenum>0)){
			return;
		}
		$chprocess();
	}
	
	function check_addarea_gameover($atime){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		if ($gametype==15){
			if($alivenum <= 0){
				\sys\gameover($atime,'end1');
				return;
			}
			if ($areanum>=$areaadd){//限时1禁
				\sys\gameover($atime,'end8');
				return;
			}
		}
		$chprocess();	
	}
}

?>