<?php

namespace instance7
{
	function init() {}

	//教程房特殊的npcinfo
	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','map','instance7'));
		if ($gametype!=17) return $chprocess();		
		return $npcinfo_instance7;
	}
	
	//教程房特殊的shopitem.config
	function get_shoplist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if ($gametype!=17) return $chprocess();		
		$file = __DIR__.'/config/shopitem.config.php';
		$l = openfile($file);
		return $l;
	}
	
	//教程房特殊的mapitem.config
	function get_itemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if ($gametype!=17) return $chprocess();
		$file = __DIR__.'/config/mapitem.config.php';
		$l = openfile($file);
		return $l;
	}
	
	//教程房特殊的trapitem.config
	function get_trapfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if ($gametype!=17) return $chprocess();
		$file = __DIR__.'/config/trapitem.config.php';
		$l = openfile($file);
		return $l;
	}
	
	//待调整
//	function check_addarea_gameover($atime){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','map'));
//		if ($gametype==17){
//			if($alivenum <= 0){
//				\sys\gameover($atime,'end1');
//				return;
//			}
//			if ($areanum>=$areaadd){//限时1禁
//				$result = $db->query("SELECT * FROM {$tablepre}players WHERE hp>0 AND type=0");
//				$wdata = $db->fetch_array($result);
//				$winner = $wdata['name'];
//				\sys\gameover($atime,'end8',$winner);
//				return;
//			}
//			\sys\rs_game(16+32);
//			return;
//		}
//		$chprocess($atime);	
//	}
}

?>