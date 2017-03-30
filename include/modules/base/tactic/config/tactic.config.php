<?php

namespace tactic
{
	//应战策略名
	$tacinfo = Array('通常','已废弃','重视防御','重视反击','重视躲避');
	
	//玩家能否使用这些策略
	$tactic_player_usable = Array(1,0,1,1,1);
	
	//应战策略对隐蔽率的加成
	$tactic_hide_obbs = Array(0,0,0,-15,20);
	
	//应战策略对与人物遭遇率的影响
	$pose_meetman_obbs = Array(0,0,0,5,-15);
	
	//应战策略对攻击力的加成（只在反击时有效）
	$tactic_attack_modifier = Array(0,20,-25,25,-50);
	
	//应战策略对防御力的加成（始终有效）
	$tactic_defend_modifier = Array(0,-10,50,-20,0);
}

?>
