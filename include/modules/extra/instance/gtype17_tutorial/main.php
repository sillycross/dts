<?php

namespace gtype17
{
	function init() {}
	
//	function checkendgame()	//跳过游戏结束判定
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys'));
//		if ($gametype==200) return;	
//		$chprocess();
//	}
//	
//	function checkcombo()	//不会连斗
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys'));
//		if ($gametype==200) return;	
//		$chprocess();
//	}

	function checkcombo(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','gameflow_combo'));
		if ($gametype==17){
			return;
		}
		$chprocess();
	}

	function check_addarea_gameover($atime){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		if ($gametype==17){
			if($alivenum <= 0){
				\sys\gameover($atime,'end1');
				return;
			}
			if ($areanum>=$areaadd){//限时1禁
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE hp>0 AND type=0");
				$wdata = $db->fetch_array($result);
				$winner = $wdata['name'];
				\sys\gameover($atime,'end8',$winner);
				return;
			}
			\sys\rs_game(16+32);
			return;
		}
		$chprocess($atime);	
	}
}

?>