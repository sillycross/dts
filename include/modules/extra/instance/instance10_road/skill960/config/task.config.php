<?php

namespace skill960
{
	//适用调查度的游戏模式
	$invscore_available_mode = array(20);
	
	//各个难度的任务编号
	$tasks_index = array
	(
		1 => array(1,2,3,4,5),
		2 => array(31,32,33,34,35),
		3 => array(61,62,63,64,65),
		4 => array(91,92,93,94,95),
		5 => array(121,122,123,124,125),
		6 => array(151,152,153,154,155),
		7 => array(181,182,183,184,185),
	);
	
	//name：任务名
	//rank: 任务等级
	//tasktype：任务类型，'battle_kill'：击杀角色，'item_search'：提交道具，'item_use'：使用道具，'special'：特殊，关联一个其他任务技能
	//taskreq：任务条件
	//'battle_kill'类型任务条件包括：'name'：NPC名称，'type'：NPC类别，'lvl'：NPC下限等级，'wepk'：NPC武器类别，'num'：需求击杀数
	//'item_search'类型任务条件包括：'itm'：道具名称列表，'itm_match'：0(默认):严格匹配，1:包含，'itmk'：道具类别列表，'num'：需求提交道具数
	//'item_use'类型任务条件包括：'itm'：道具名称列表，'itm_match'：0(默认):严格匹配，1:包含，'itmk'：道具类别列表，'num'：需求提交道具数
	//'special'类型任务条件包括：'skillid'：任务技能编号，'lvl'：任务技能需求等级
	//reward：任务奖励，'money'：金钱，'exp'：经验，'item'：道具，'invscore'：调查度
	$tasks_info = array
	(
		1 => array
		(
			'name' => '小试牛刀',
			'rank' => 1,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>90,'num'=>5),
			'reward' => array('money'=>500,'invscore'=>8),
		),
		2 => array
		(
			'name' => '年年有鱼',
			'rank' => 1,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('凸眼鱼','安康鱼','河豚鱼'),'num'=>3),
			'reward' => array('invscore'=>8,'item'=>array(array('itm'=>'淡紫色的技能核心','itmk'=>'SC02','itme'=>1,'itms'=>1,'itmsk'=>''),)),
		),
		3 => array
		(
			'name' => '专业报社',
			'rank' => 1,
			'tasktype' => 'item_use',
			'taskreq' => array('itm'=>array('凸眼鱼','毒药'),'num'=>3),
			'reward' => array('invscore'=>8),
		),
		4 => array
		(
			'name' => '专业报社',
			'rank' => 1,
			'tasktype' => 'item_use',
			'taskreq' => array('itm'=>array('凸眼鱼','毒药'),'num'=>3),
			'reward' => array('invscore'=>8),
		),
		5 => array
		(
			'name' => '专业报社',
			'rank' => 1,
			'tasktype' => 'item_use',
			'taskreq' => array('itm'=>array('凸眼鱼','毒药'),'num'=>3),
			'reward' => array('invscore'=>8),
		),
		31 => array
		(
			'name' => '测试任务Lv.2',
			'rank' => 2,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		32 => array
		(
			'name' => '测试任务Lv.2',
			'rank' => 2,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		33 => array
		(
			'name' => '测试任务Lv.2',
			'rank' => 2,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		34 => array
		(
			'name' => '测试任务Lv.2',
			'rank' => 2,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		35 => array
		(
			'name' => '测试任务Lv.2',
			'rank' => 2,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		61 => array
		(
			'name' => '测试任务Lv.3',
			'rank' => 3,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		62 => array
		(
			'name' => '测试任务Lv.3',
			'rank' => 3,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		63 => array
		(
			'name' => '测试任务Lv.3',
			'rank' => 3,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		64 => array
		(
			'name' => '测试任务Lv.3',
			'rank' => 3,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		65 => array
		(
			'name' => '测试任务Lv.3',
			'rank' => 3,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		91 => array
		(
			'name' => '测试任务Lv.4',
			'rank' => 4,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		92 => array
		(
			'name' => '测试任务Lv.4',
			'rank' => 4,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		93 => array
		(
			'name' => '测试任务Lv.4',
			'rank' => 4,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		94 => array
		(
			'name' => '测试任务Lv.4',
			'rank' => 4,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		95 => array
		(
			'name' => '测试任务Lv.4',
			'rank' => 4,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		121 => array
		(
			'name' => '测试任务Lv.5',
			'rank' => 5,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		122 => array
		(
			'name' => '测试任务Lv.5',
			'rank' => 5,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		123 => array
		(
			'name' => '测试任务Lv.5',
			'rank' => 5,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		124 => array
		(
			'name' => '测试任务Lv.5',
			'rank' => 5,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		125 => array
		(
			'name' => '测试任务Lv.5',
			'rank' => 5,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		151 => array
		(
			'name' => '测试任务Lv.6',
			'rank' => 6,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		152 => array
		(
			'name' => '测试任务Lv.6',
			'rank' => 6,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		153 => array
		(
			'name' => '测试任务Lv.6',
			'rank' => 6,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		154 => array
		(
			'name' => '测试任务Lv.6',
			'rank' => 6,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		155 => array
		(
			'name' => '测试任务Lv.6',
			'rank' => 6,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		181 => array
		(
			'name' => '测试任务Lv.7',
			'rank' => 7,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		182 => array
		(
			'name' => '测试任务Lv.7',
			'rank' => 7,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		183 => array
		(
			'name' => '测试任务Lv.7',
			'rank' => 7,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		184 => array
		(
			'name' => '测试任务Lv.7',
			'rank' => 7,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
		185 => array
		(
			'name' => '测试任务Lv.7',
			'rank' => 7,
			'tasktype' => 'item_search',
			'taskreq' => array('itmlist'=>array('黄鸡方块'),'num'=>1),
			'reward' => array('invscore'=>8),
		),
	);
}

?>
