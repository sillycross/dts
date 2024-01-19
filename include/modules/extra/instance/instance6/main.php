<?php

namespace instance6
{
	function init() {}
	
	function get_shopconfig(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if ($gametype==16){
			$file = __DIR__.'/config/shopitem.config.php';
			$sl6 = openfile($file);
			return $sl6;
		}else return $chprocess();
	}
	
	function checkcombo($time){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ( $gametype==16 && \map\get_area_wavenum() < 2 && $alivenum>0 ){
			return;
		}
		$chprocess($time);
	}
	
	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','instance6'));
		if (16 == $gametype){
			return $npcinfo_instance6;
		}else return $chprocess();
	}
	
	function rs_game($xmode = 0) 	//开局天气初始化
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($xmode);
		
		eval(import_module('sys'));
		if (($gametype==16)&&($xmode & 2)) 
		{
			$weather = 1;
		}
	}
	
	function check_addarea_gameover($atime){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($gametype==16){
			if($alivenum <= 0){
				\sys\gameover($atime,'end1');
				return;
			}
			if (\map\get_area_wavenum() >= 4){//限时4禁
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE hp>0 AND type=0 ORDER BY card LIMIT 1");
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