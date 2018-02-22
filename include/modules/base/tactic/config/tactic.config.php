<?php

namespace tactic
{
	//应战策略名
	$tacinfo = Array('通常','重视攻击（已废弃）','重视防御','重视反击','重视躲避');
	
	//应战策略介绍
	$tacticdesc = array(
		'不注重任何一方面的策略',
		'',
		'随时准备防御敌方攻击和陷阱的策略',
		'随时准备反击的策略',
		'试图回避敌人、陷阱和禁区的策略'
	);
	
	//应战策略备注
	$tacticremark = array(
		'',
		'',
		'有75%概率使陷阱伤害-25%',
		'反击率+30%',
		'无法反击。陷阱回避率+20%，连斗前可以躲避禁区。'
	);
	
	//玩家能否使用这些策略
	$tactic_player_usable = Array(0,0,1,1,1);
	
	//应战策略对隐蔽率的加成
	$tactic_hide_obbs = Array(0,0,0,-15,25);
	
	//应战策略对与人物遭遇率的影响
	$tactic_meetman_obbs = Array(0,0,0,10,-15);
	
	//应战策略对攻击力的加成
	$tactic_attack_modifier = Array(0,20,-25,25,-50);
	
	//应战策略对防御力的加成
	$tactic_defend_modifier = Array(0,-10,50,0,-10);
}

?>
