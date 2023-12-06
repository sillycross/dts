<?php

namespace npc_action
{
	$npc_action_min_intv = 30;//NPC行动的最小间隔时间，单位秒。间隔时间少于这个或者没设置的，以这个为准
	
	$npc_action_data = Array(//定义哪些NPC会自动行动。
		'冴月 麟' => Array(
			'intv' => 30,//行动间隔
			'devi' => Array(-15, 15),//行动间隔偏差值，第一个数值为负偏差，第二个数值为正偏差，会在范围内随机取值
			'actions' => Array(//会执行的行动及对应的概率。可选项有move（随机移动）、chase（追杀）、evade（躲避）
				'move' => 100,
				//'chase' => 100,
				//'evade' => 100,
			),
			'setting' => Array(//行动设定。
				'move' => Array(
					'maplist' => Array(99),//随机移动，会在列表里选一个地点，如果为99则随机选一个
					'avoid_forbidden' => 1,//随机移动是否躲避禁区
					'avoid_dangerous' => 1,//随机移动是否躲避危险地图
					'addchat' => 1,//是否发送聊天记录
					'addchat_txt' => Array(//发送特定的聊天记录，用<:para1:>和<:para2:>代表出发地和目的地
						'【MOVED TO <:para2:>.】',
					),
				),
				'chase' => Array(
					'object' => Array('r'),//追逐对象，r为随机选一个玩家，s为追头名，w为追最弱的，其他值为追踪名字为该值的角色（可以是NPC）
				),
				'evade' => Array(
					'object' => Array('r'),//躲避对象，r为随机选一个玩家，s为躲头名，w为躲最弱的，其他值为躲避名字为该值的角色（可以是NPC）
				),
			),
		)
	);
}
?>