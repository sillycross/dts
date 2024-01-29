<?php

namespace npc_action
{
	$npc_action_gametype = Array(18,13);//开启NPC行动的游戏模式
	$npc_action_min_intv = 30;//NPC行动的最小间隔时间，单位秒。间隔时间少于这个或者没设置的，以这个为准
	
	$npc_action_data = Array(//定义哪些NPC会自动行动。
		'冴月 麟' => Array(
			'intv' => 240,//行动间隔
			'devi' => Array(-60, 60),//行动间隔偏差值，第一个数值为负偏差，第二个数值为正偏差，会在范围内随机取值
			'actions' => Array(//会执行的行动及对应的概率。具体执行时先取满足条件的行动，然后根据比例来判定概率。可选项有move（随机移动）、chase（追杀）、evade（躲避）
				'move' => 1,
				'chase' => 100,
				'ambush' => 100,
				//'evade' => 100,//注意提前批行动会单独计算概率
			),
			
			'setting' => Array(//行动设定。
				'move' => Array(
					'moveto_list' => Array(99),//随机移动，会在列表里选一个地点，如果为99则随机选一个
					'avoid_forbidden' => 1,//随机移动是否躲避禁区
					'avoid_dangerous' => 1,//随机移动是否躲避危险地图
					//'need_awake' => 1,//是否在清醒状态（先制任意玩家后）才会开始执行本项行动
					//'need_rage_GE' => 100,//怒气最少要到达哪个值才会开始执行本行动
					//'need_rage_LE' => 30,//怒气不能超过哪个值才会开始执行本行动
					//'rage_change_after_action' => 0,//执行后怒气变化量，可正可负
					'addchat' => 1,//是否发送聊天记录
					'addchat_txt' => Array(//发送特定的聊天记录，用<:para1:>和<:para2:>代表出发地和目的地
						'【MOVED TO "<:para2:>". 】',
					),
				),
				'guard' => Array(//警惕，使下一次行动时间提前，也可以做为空技能
					'guard_time' => 180,
				),
				'chase' => Array(
					'object' => Array('W'),//追逐对象，R为随机选一个玩家，T为追头名，B为追最弱的，P:XXX为追踪名字为XXX的玩家，N:XXX为追踪名字为XXX的NPC，W为追上一次与自己作战的玩家
					'avoid_forbidden' => 0,//是否躲避禁区
					'avoid_dangerous' => 0,//是否躲避危险地图
					//'need_awake' => 1,//是否在清醒状态（先制任意玩家后）才会开始执行本项行动
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
					'object' => Array('B'),//躲避对象，R为随机选一个玩家，T为躲头名，B为躲最弱的，P:XXX为追踪名字为XXX的玩家，N:XXX为追踪名字为XXX的NPC，W为追上一次与自己作战的玩家
					'avoid_forbidden' => 1,//是否躲避禁区
					'avoid_dangerous' => 1,//是否躲避危险地图
					//'need_awake' => 1,//是否在清醒状态（先制任意玩家后）才会开始执行本项行动
					//'need_rage_GE' => 30,//怒气最少要到达哪个值才会开始执行本行动
					'need_rage_LE' => 70,//怒气不能超过哪个值才会开始执行本行动
					//'rage_change_after_action' => -3,//执行后怒气变化量，可正可负
					'addchat' => 1,//是否发送聊天记录
					'addchat_txt' => Array(//发送特定的聊天记录，用<:para1:>和<:para2:>代表出发地和目的地，用<:para3:>代表追踪目标
						'【EVADE: "<:para3:>" . MOVED TO "<:para2:>". 】',
					),
				),
				'ambush' => Array(//问候前允许偷袭一次！就算不是探索也有概率先制玩家，但需要在同一个地图
					'early_action' => 1,//是否为提前批行动，如果是则不满足条件后还会再执行一遍其他行动
					'object' => Array('A'),//偷袭对象，A为不分条件，S为偷袭较强的，L为偷袭较弱的，P:XXX为名字为XXX的玩家，W为偷袭上一次与自己作战的玩家
					//'need_awake' => 1,//是否在清醒状态（先制任意玩家后）才会开始执行本项行动
					'need_rage_GE' => 30,//怒气最少要到达哪个值才会开始执行本行动
					'rage_change_after_action' => -5,//执行后怒气变化量，可正可负
					'ambush_findrate_buff' => 0,//先攻率加成（减成）
					'action_if_fail' => 'guard',//偷袭失败后执行的动作
					'addchat' => 1,//是否发送聊天记录
					'addchat_txt' => Array(//发送特定的聊天记录，用<:para1:>和<:para2:>代表出发地和目的地，用<:para3:>代表追踪目标
						'【AMBUSH: "<:para3:>" BEFORE AISATSU! 】',
					),
				),
			),
		),	
		//END OF 冴月麟
		
		'某四面' => Array(
			'intv' => 240,//行动间隔
			'devi' => Array(-60, 60),//行动间隔偏差值，第一个数值为负偏差，第二个数值为正偏差，会在范围内随机取值
			'actions' => Array(//会执行的行动及对应的概率。具体执行时先取满足条件的行动，然后根据比例来判定概率。可选项有move（随机移动）、chase（追杀）、evade（躲避）
				'move' => 1,
				'chase' => 100,
				'ambush' => 100,
			),
			
			'setting' => Array(//行动设定。
				'move' => Array(
					'moveto_list' => Array(99),//随机移动，会在列表里选一个地点，如果为99则随机选一个
					'avoid_forbidden' => 1,//随机移动是否躲避禁区
					'avoid_dangerous' => 1,//随机移动是否躲避危险地图
					//'need_awake' => 1,//是否在清醒状态（先制任意玩家后）才会开始执行本项行动
					//'need_rage_GE' => 10,//怒气最少要到达哪个值才会开始执行本行动
					//'need_rage_LE' => 30,//怒气不能超过哪个值才会开始执行本行动
					//'rage_change_after_action' => 0,//执行后怒气变化量，可正可负
					'addchat' => 1,//是否发送聊天记录
					'addchat_txt' => Array(//发送特定的聊天记录，用<:para1:>和<:para2:>代表出发地和目的地
						'♪ Then they’re eatin’ every apple in "<:para2:>"’s apple tree ♪',
						'♪ They don’t care about "<:para2:>", not zilch, no, nothin’ ♪',
						'♪ ’ Cept bringin’  about "<:para2:>"’ s destruction ♪'
					),
				),
				'guard' => Array(//警惕，使下一次行动时间提前，也可以做为空技能
					'guard_time' => 180,
				),
				'evade' => Array(
					'early_action' => 1,//是否为提前批行动，如果是则不满足条件后还会再执行一遍其他行动
					'object' => Array('T'),//躲避对象，R为随机选一个玩家，T为躲头名，B为躲最弱的，P:XXX为追踪名字为XXX的玩家，N:XXX为追踪名字为XXX的NPC，W为追上一次与自己作战的玩家
					'avoid_forbidden' => 1,//是否躲避禁区
					'avoid_dangerous' => 1,//是否躲避危险地图
					//'need_awake' => 1,//是否在清醒状态（先制任意玩家后）才会开始执行本项行动
					'need_rage_GE' => 30,//怒气最少要到达哪个值才会开始执行本行动
					//'need_rage_LE' => 70,//怒气不能超过哪个值才会开始执行本行动
					'rage_change_after_action' => -3,//执行后怒气变化量，可正可负
					'addchat' => 1,//是否发送聊天记录
					'addchat_txt' => Array(//发送特定的聊天记录，用<:para1:>和<:para2:>代表出发地和目的地，用<:para3:>代表追踪目标
						'♪ "<:para3:>" care for their young just like we ponies do ♪',
					),
				),
				'ambush' => Array(//问候前允许偷袭一次！就算不是探索也有概率先制玩家，但需要在同一个地图
					'early_action' => 1,//是否为提前批行动，如果是则不满足条件后还会再执行一遍其他行动
					'object' => Array('A'),//偷袭对象，A为不分条件，S为偷袭较强的，L为偷袭较弱的，P:XXX为名字为XXX的玩家，W为偷袭上一次与自己作战的玩家
					//'need_awake' => 1,//是否在清醒状态（先制任意玩家后）才会开始执行本项行动
					'need_rage_GE' => 30,//怒气最少要到达哪个值才会开始执行本行动
					'rage_change_after_action' => -5,//执行后怒气变化量，可正可负
					'ambush_findrate_buff' => 0,//先攻率加成（减成）
					'action_if_fail' => 'guard',//偷袭失败后执行的动作
					'addchat' => 1,//是否发送聊天记录
					'addchat_txt' => Array(//发送特定的聊天记录，用<:para1:>和<:para2:>代表出发地和目的地，用<:para3:>代表追踪目标
						'♪ "<:para3:>"’ve crossed the line, it’s time to fight them back! ♪',
					),
				),
			),
		),
		//END OF 某四面
		
		'便当盒' => Array(
			'intv' => 60,//行动间隔
			'devi' => Array(-10, 10),//行动间隔偏差值，第一个数值为负偏差，第二个数值为正偏差，会在范围内随机取值
			'actions' => Array(//会执行的行动及对应的概率。具体执行时先取满足条件的行动，然后根据比例来判定概率。可选项有move（随机移动）、chase（追杀）、evade（躲避）
				'move' => 100,
				'guard' => 100,
			),
			
			'setting' => Array(//行动设定。
				'move' => Array(
					'early_action' => 1,//是否为提前批行动，如果是则不满足条件后还会再执行一遍其他行动
					'moveto_list' => Array(99),//随机移动，会在列表里选一个地点，如果为99则随机选一个
					'avoid_forbidden' => 1,//随机移动是否躲避禁区
					'avoid_dangerous' => 1,//随机移动是否躲避危险地图
					//'need_awake' => 1,//是否在清醒状态（先制任意玩家后）才会开始执行本项行动
					'need_rage_GE' => 100,//怒气最少要到达哪个值才会开始执行本行动
					//'need_rage_LE' => 30,//怒气不能超过哪个值才会开始执行本行动
					'rage_change_after_action' => -100,//执行后怒气变化量，可正可负
					'addchat' => 1,//是否发送聊天记录
					'addchat_txt' => Array(//发送特定的聊天记录，用<:para1:>和<:para2:>代表出发地和目的地
						'ybb，赶紧切地图！'
					),
				),
				'guard' => Array(//警惕，使下一次行动时间提前，也可以做为空技能
					'guard_time' => 0,
				),
			),
		),
		//END OF 便当盒
		
		'KHIBIKI《黑曲》' => Array(
			'type' => 11,//如果有设置，会额外判定type是否一致，有同名NPC时使用
			'intv' => 60,//行动间隔
			'devi' => Array(-10, 10),//行动间隔偏差值，第一个数值为负偏差，第二个数值为正偏差，会在范围内随机取值
			'actions' => Array(//会执行的行动及对应的概率。具体执行时先取满足条件的行动，然后根据比例来判定概率。可选项有move（随机移动）、chase（追杀）、evade（躲避）
				'move' => 100,
				'guard' => 100,
			),
			
			'setting' => Array(//行动设定。
				'move' => Array(
					'moveto_list' => Array(99),//随机移动，会在列表里选一个地点，如果为99则随机选一个
					'avoid_forbidden' => 1,//随机移动是否躲避禁区
					'avoid_dangerous' => 1,//随机移动是否躲避危险地图
					//'need_awake' => 1,//是否在清醒状态（先制任意玩家后）才会开始执行本项行动
					'need_rage_GE' => 100,//怒气最少要到达哪个值才会开始执行本行动
					//'need_rage_LE' => 30,//怒气不能超过哪个值才会开始执行本行动
					'rage_change_after_action' => -100,//执行后怒气变化量，可正可负
					'addchat' => 1,//是否发送聊天记录
					'addchat_txt' => Array(//发送特定的聊天记录，用<:para1:>和<:para2:>代表出发地和目的地
						'OAO?'
					),
				),
				'guard' => Array(//警惕，使下一次行动时间提前，也可以做为空技能
					'guard_time' => 0,
				),
			),
		),
		//END OF KHIBIKI《黑曲》
		
		'一一五 i' => Array(
			'intv' => 90,//行动间隔
			'devi' => Array(-30, 30),//行动间隔偏差值，第一个数值为负偏差，第二个数值为正偏差，会在范围内随机取值
			'actions' => Array(//会执行的行动及对应的概率。具体执行时先取满足条件的行动，然后根据比例来判定概率。可选项有move（随机移动）、chase（追杀）、evade（躲避）
				'chase' => 100,
				'ambush' => 100,
			),
			
			'setting' => Array(//行动设定。
				'guard' => Array(//警惕，使下一次行动时间提前，也可以做为空技能
					'guard_time' => 30,
				),
				'chase' => Array(
					'object' => Array('T'),//追逐对象，R为随机选一个玩家，T为追头名，B为追最弱的，P:XXX为追踪名字为XXX的玩家，N:XXX为追踪名字为XXX的NPC，W为追上一次与自己作战的玩家
					'avoid_forbidden' => 0,//是否躲避禁区
					'avoid_dangerous' => 0,//是否躲避危险地图
					//'need_awake' => 1,//是否在清醒状态（先制任意玩家后）才会开始执行本项行动
					//'need_rage_GE' => 30,//怒气最少要到达哪个值才会开始执行本行动
					//'need_rage_LE' => 30,//怒气不能超过哪个值才会开始执行本行动
					//'rage_change_after_action' => -3,//执行后怒气变化量，可正可负
					'addchat' => 1,//是否发送聊天记录
					'addchat_txt' => Array(//发送特定的聊天记录，用<:para1:>和<:para2:>代表出发地和目的地，用<:para3:>代表追踪目标
						'发动魔法卡『位置移动』！',
						'曾经有一个游戏开创了『传送击杀』这么一个东西，可惜大逃杀在传送后不能马上攻击。',
						'听说会打枪的猫又妖怪是先尾行再扔手雷的，但我觉得能一击毙命的时候还是大刺刺追击更好。',
					),
				),
				'ambush' => Array(//问候前允许偷袭一次！就算不是探索也有概率先制玩家，但需要在同一个地图
					'early_action' => 1,//是否为提前批行动，如果是则不满足条件后还会再执行一遍其他行动
					'object' => Array('A'),//偷袭对象，A为不分条件，S为偷袭较强的，L为偷袭较弱的，P:XXX为名字为XXX的玩家，W为偷袭上一次与自己作战的玩家
					//'need_awake' => 1,//是否在清醒状态（先制任意玩家后）才会开始执行本项行动
					//'need_rage_GE' => 30,//怒气最少要到达哪个值才会开始执行本行动
					//'rage_change_after_action' => -5,//执行后怒气变化量，可正可负
					'ambush_findrate_buff' => 20,//先攻率加成（减成）
					'action_if_fail' => 'guard',//偷袭失败后执行的动作
					'addchat' => 1,//是否发送聊天记录
					'addchat_txt' => Array(//发送特定的聊天记录，用<:para1:>和<:para2:>代表出发地和目的地，用<:para3:>代表追踪目标
						'难道你以为我只是一个血条比较厚的木桩？',
						'我的回合，抽卡，进战阶！',
						'SURPRISE——',
					),
				),
			),
		),
	);
}
?>