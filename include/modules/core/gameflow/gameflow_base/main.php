<?php

namespace gameflow_base
{
	function init() {}
	
	function gamestate_prepare_game()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		
		$gamenum++;
		$gamestate = 10;
		$hdamage = 0;
		$hplayer = '';
		$noisemode = '';
		\sys\reset_game();
		\sys\rs_game(1+2+4+8+16+32);
	
	}
	
	function gamestate_start_game()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		
		$gamestate = 20;
		addnews($starttime,'newgame',$gamenum);
		systemputchat($starttime,'newgame');
	}
	
	function gamestateupdate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		if(!$gamestate) { //判定游戏准备
			if(($starttime)&&($now > $starttime - $startmin*60)) {
				gamestate_prepare_game();
			}
		}
		if($gamestate == 10) {//判定游戏开始
			if($now >= $starttime) {
				gamestate_start_game();
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
