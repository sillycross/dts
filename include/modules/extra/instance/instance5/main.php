<?php

namespace instance5
{
	function init() {}
	
	function get_shoplist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','instance5'));
		if ($gametype==15){
			$file = __DIR__.'/config/shopitem.config.php';
			$sl5 = openfile($file);
			return $sl5;
		}else return $chprocess();
	}
	
	function checkcombo(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','gameflow_combo'));
		if (($gametype==15)&&($areanum<$areaadd*2)&&($alivenum>0)){
			return;
		}
		$chprocess();
	}
	
	function rs_game($xmode = 0) 	//开局天气初始化
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($xmode);
		
		eval(import_module('sys'));
		if (($gametype==15)&&($xmode & 2)) 
		{
			$weather = 1;
		}
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