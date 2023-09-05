<?php

namespace instance7
{
	function init() {}
	
	//教程固定卡片（教程技能+开局紧急药剂）
	function get_enter_battlefield_card($card){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$card = $chprocess($card);
		if(17==$gametype) {
			$card = 1000;
		}	
		return $card;
	}

	//教程房特殊的npcinfo
	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','instance7'));
		if (17 == $gametype){
			return $npcinfo_instance7;
		}else return $chprocess();
	}
	
	//教程房特殊的shopitem.config
	function get_shopconfig(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (17 == $gametype){
			$file = __DIR__.'/config/shopitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	//教程房特殊的mapitem.config
	function get_itemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (17 == $gametype){
			$file = __DIR__.'/config/mapitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	//教程房特殊的trapitem.config
	function get_trapfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (17 == $gametype){
			$file = __DIR__.'/config/trapitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	//教程房特殊的stitem.config
	function get_startingitemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (17 == $gametype){
			$file = __DIR__.'/config/stitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
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