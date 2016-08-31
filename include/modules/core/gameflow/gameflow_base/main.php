<?php

namespace gameflow_base
{
	function init() {}
	
	function gamestate_prepare_game()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		
		$gamenum++; $gametype=0;
		\sys\reset_game();
		\sys\prepare_new_game();
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
	}
	
	function checkendgame(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys')); 
		if($gamestate >= 40) {
			//队伍胜利模式游戏结束判断
			if (in_array($gametype,$teamwin_mode))
			{
				$result = $db->query("SELECT teamID FROM {$tablepre}players WHERE type = 0 AND hp > 0");
				$flag=1; $first=1; 
				while($data = $db->fetch_array($result)) 
				{
					if ($first) 
					{ 
						$first=0; $firstteamID=$data['teamID'];
					}
					else  if ($firstteamID!=$data['teamID'] || !$data['teamID'])
					{
						//如果有超过一种teamID，或有超过一个人没有teamID，则游戏还未就结束
						$flag=0; break;
					}
				}
				if ($flag && !$first)
				{
					\sys\gameover();
				}
			}
			else
			{
				if($alivenum <= 1 && $gametype!=2) 
				{
					\sys\gameover();
				}
			}
		}
	}
	
	function updategame()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess();
		
		gamestateupdate();
		
		checkendgame();
	}
}

?>
