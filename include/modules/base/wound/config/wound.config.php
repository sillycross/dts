<?php

namespace wound
{
	//可以受伤的部位列表
	$inf_place = 'bhaf';
	
	//每个受伤状态名称简称（用于profile显示）
	$infinfo = Array(
		'b' => '<span class="red">胸</span>', 
		'h' => '<span class="red">头</span>', 
		'a' => '<span class="red">腕</span>', 
		'f' => '<span class="red">足</span>'
	);
	
	//每个受伤状态的名称
	$infname = Array(
		'b' => '<span class="red">胸部受伤</span>', 
		'h' => '<span class="red">头部受伤</span>', 
		'a'=> '<span class="red">腕部受伤</span>', 
		'f'=> '<span class="red">足部受伤</span>'
	);
	
	//受伤状态对应的特效技能编号（参见skills）
	//获得新的受伤状态时会自动调用skill_acquire，治疗好了受伤状态时会自动调用skill_lost
	//但是载入原有受伤状态的技能，和保存时失去技能还是需要各个skill自己完成
	$infskillinfo = Array('b' => 1, 'h' => 2, 'a' => 3, 'f' => 4);
	
	//各种攻击方式可能导致受伤的部位
	$wep_infatt = Array('N' => 'b', 'P' => 'bha', 'K' =>'bhaf', 'G' =>'bhaf', 'C'=> 'bh', 'D' => 'bhaf', 'F'=> 'bhaf', 'J'=> 'bhaf', 'B' => 'bhaf');
	
	//各种攻击方式的致伤率
	$wep_infobbs = Array('N' => 5, 'P' => 15, 'K' => 55, 'G' => 25, 'C' => 10, 'D' => 55, 'F' => 30, 'J'=> 75, 'B' => 40);
	
	//包扎伤口需要的体力
	$inf_recover_sp_cost = 50;
}

?>