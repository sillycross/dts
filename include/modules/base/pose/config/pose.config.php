<?php

namespace pose
{
	//姿势名
	$poseinfo = Array('通常','作战姿态','强袭姿态','探物姿态','偷袭姿态','治疗姿态');
	
	//姿态介绍
	$posedesc = Array(
		'最普通的姿态',
		'以备战为目的，提升攻防，略微提升角色发现率',
		'大幅提升被动先攻率的姿态，通常不可选',
		'以寻找物品为目的，提升道具发现率，但是影响攻击力',
		'以先发制人为目的，提升角色发现率以及先手率，但是影响防御力',
		'以自我治疗为目的，提升恢复能力，其他数值大幅恶化'
	);
	
	//姿态备注
	$poseremark = Array(
		'-',
		'陷阱遭遇率+1%',
		'-',
		'陷阱遭遇率+3%；移动和探索时必定会探索一次道具',
		'-',
		'不能反击；睡眠、治疗、静养恢复量3倍且异常恢复速度加快；<br>补给效果+20%'
	);
	
	//玩家是否可以使用上述姿势
	$pose_player_usable = Array(0,1,0,1,1,1);
	
	//姿势对物品发现率的修正
	$pose_itemfind_obbs = Array(0,0,0,25,0,-25);
	
	//姿势对人物发现率的修正
	//$pose_findman_obbs = Array(0,0,0,20,-10,25);
	$pose_findman_obbs = Array(0,5,0,0,10,0);
	
	//姿势对隐蔽率的修正
	//$pose_hide_obbs = Array(0,-25,0,-10,10,-25);
	$pose_hide_obbs = Array(0,0,0,-10,10,-25);
	
	//姿势对先攻率的修正（探索遭遇敌人，有能力主动选择战斗的几率）
	//$pose_active_obbs = Array(0,-4,0,0,25,-35);
	$pose_active_obbs = Array(0,5,0,0,25,-35);
	
	//姿势对敌人先攻自己几率的修正（被敌人探索发现，注意这里修正的是敌人的几率，即负数对自己有利）
	//$pose_dactive_obbs = Array(0,4,-40,0,-10,35);
	//涉及到NPC先制率，最好少改
	$pose_dactive_obbs = Array(0,0,-40,0,-10,35);
	
	//姿态对攻击力的修正
	//$pose_attack_modifier = Array(0,60,0,-25,25,-50);
	$pose_attack_modifier = Array(0,40,0,-25,25,-50);
	
	//姿态对防御力的修正
	//$pose_defend_modifier = Array(0,30,0,-20,-25,-50);
	$pose_defend_modifier = Array(0,20,0,10,-25,-50);
	
	//姿态对服用补给效果的修正（以100为基准的增减值）
	$pose_edible_modifier = Array(0,0,0,0,0,20);
}

?>
