<?php

namespace npc_action
{
	$npc_action_gametype = Array(18);//开启NPC行动的游戏模式
	$npc_action_min_intv = 30;//NPC行动的最小间隔时间，单位秒。间隔时间少于这个或者没设置的，以这个为准
	
	$npc_action_data = Array(//定义哪些NPC会自动行动。
		'冴月 麟' => Array(
			'intv' => 30,//行动间隔
			'devi' => Array(-15, 15),//行动间隔偏差值，第一个数值为负偏差，第二个数值为正偏差，会在范围内随机取值
			'actions' => Array(//会执行的行动及对应的概率。具体执行时先取满足条件的行动，然后根据比例来判定概率。可选项有move（随机移动）、chase（追杀）、evade（躲避）
				'move' => 1,
				'chase' => 100,
				//'evade' => 100,//注意提前批行动会单独计算概率
			),
			
			'setting' => Array(//行动设定。
				'move' => Array(
					'moveto_list' => Array(99),//随机移动，会在列表里选一个地点，如果为99则随机选一个
					'avoid_forbidden' => 1,//随机移动是否躲避禁区
					'avoid_dangerous' => 1,//随机移动是否躲避危险地图
					//'need_awake' => 1,//是否在清醒状态（被打后）才会开始执行本项行动
					//'need_rage_GE' => 100,//怒气最少要到达哪个值才会开始执行本行动
					//'need_rage_LE' => 30,//怒气不能超过哪个值才会开始执行本行动
					//'rage_change_after_action' => 0,//执行后怒气变化量，可正可负
					'addchat' => 1,//是否发送聊天记录
					'addchat_txt' => Array(//发送特定的聊天记录，用<:para1:>和<:para2:>代表出发地和目的地
						'【MOVED TO "<:para2:>". 】',
					),
				),
				'chase' => Array(
					'object' => Array('W'),//追逐对象，R为随机选一个玩家，T为追头名，B为追最弱的，P:XXX为追踪名字为XXX的玩家，N:XXX为追踪名字为XXX的NPC，W为追上一次与自己作战的玩家
					'avoid_forbidden' => 0,//是否躲避禁区
					'avoid_dangerous' => 0,//是否躲避危险地图
					//'need_awake' => 1,//是否在清醒状态（被打后）才会开始执行本项行动
					'need_rage_GE' => 30,//怒气最少要到达哪个值才会开始执行本行动
					//'need_rage_LE' => 30,//怒气不能超过哪个值才会开始执行本行动
					'rage_change_after_action' => -3,//执行后怒气变化量，可正可负
					'addchat' => 1,//是否发送聊天记录
					'addchat_txt' => Array(//发送特定的聊天记录，用<:para1:>和<:para2:>代表出发地和目的地，用<:para3:>代表追踪目标
						'【CHASE: "<:para3:>" . MOVED TO "<:para2:>". 】',
					),
					
				),
				'evade' => Array(
					'early_action' => 1,//是否为提前批行动，如果是则不满足条件后还会再执行一遍其他行动
					'object' => Array('B'),//躲避对象，R为随机选一个玩家，T为躲头名，B为躲最弱的，P:XXX为追踪名字为XXX的玩家，N:XXX为追踪名字为XXX的NPC，W为追上一次与自己作战的玩家（需skill1007支持）
					'avoid_forbidden' => 1,//是否躲避禁区
					'avoid_dangerous' => 1,//是否躲避危险地图
					//'need_awake' => 1,//是否在清醒状态（被打后）才会开始执行本项行动
					//'need_rage_GE' => 30,//怒气最少要到达哪个值才会开始执行本行动
					'need_rage_LE' => 70,//怒气不能超过哪个值才会开始执行本行动
					//'rage_change_after_action' => -3,//执行后怒气变化量，可正可负
					'addchat' => 1,//是否发送聊天记录
					'addchat_txt' => Array(//发送特定的聊天记录，用<:para1:>和<:para2:>代表出发地和目的地，用<:para3:>代表追踪目标
						'【EVADE: "<:para3:>" . MOVED TO "<:para2:>". 】',
					),
				),
			),
		)
	);
}
?>