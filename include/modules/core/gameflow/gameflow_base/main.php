<?php

namespace gameflow_base
{
	function init() {}
	
	function gamestateupdate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		if(!$gamestate) { //判定游戏准备
			if(($starttime)&&($now > $starttime - $startmin*60)) {
				$gamenum++;
				$gamestate = 10;
				$hdamage = 0;
				$hplayer = '';
				$noisemode = '';
				\sys\reset_game();
				\sys\rs_game(1+2+4+8+16+32);
			}
		}
		if($gamestate == 10) {//判定游戏开始
			if($now >= $starttime) {
				$gamestate = 20;
				addnews($starttime,'newgame',$gamenum);
				systemputchat($starttime,'newgame');
			}
		}
		
		if($gamestate >= 40) {
			if($alivenum <= 1) {
				\sys\gameover();
			}
		}
	}
	
	function updategame()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		gamestateupdate();
		$chprocess();
	}
}

?>
