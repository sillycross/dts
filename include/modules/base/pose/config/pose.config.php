<?php

namespace pose
{
	//姿势名
	$poseinfo = Array('通常','作战姿态','强袭姿态','探物姿态','偷袭姿态','治疗姿态');
	
	//玩家是否可以使用上述姿势
	$pose_player_usable = Array(1,1,0,1,1,1);
	
	//姿势对物品发现率的修正
	$pose_itemfind_obbs = Array(0,0,0,25,-10,-25);
	
	//姿势对人物遭遇率的修正
	$pose_meetman_obbs = Array(0,0,0,20,-10,25);
	
	//姿势对隐蔽率的修正
	$pose_hide_obbs = Array(0,-25,0,-10,10,-25);
	
	//姿势对先攻率的修正（探索遭遇敌人，有能力主动选择战斗的几率）
	$pose_active_obbs = Array(0,-4,0,0,25,-35);
	
	//姿势对敌人先攻自己几率的修正（被敌人探索发现，注意这里修正的是敌人的几率，即负数对自己有利）
	$pose_dactive_obbs = Array(0,4,-50,0,0,35);
	
	//姿态对自己主动或先制攻击敌人的攻击力修正
	$pose_attack_modifier = Array(0,50,0,-25,25,-50);
	
	//姿态对自己防御力的修正（这是始终生效的）
	$pose_defend_modifier = Array(0,25,0,-20,-35,-50);
}

?>
