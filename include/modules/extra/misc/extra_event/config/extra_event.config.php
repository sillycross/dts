<?php

namespace extra_event
{
	$extra_event_gametype = Array(18);//开启扩展事件的游戏类型，目前只在荣耀模式

	$extra_event_list = Array(//扩展事件定义列表，要开启事件必须设置此参数。键名为地图，键值为该地图允许的扩展事件
		4 => Array(1),
	);

	$extra_event_allow_exit = Array(1);//允许中途退出的事件

	$extra_event_occurred_times_limit = Array(//事件次数限制，不设置为不限制
		1 => 1,
	);

	$extra_event_texts = Array(//注意这里只放文本
		1 => Array(
			'title' => '愿望',//事件标题。目前暂不显示。位于索拉利斯
			'overview' => '一个不属于你的念头在你的脑海中涌现。<br>',
			
			'texts' => Array(//这里只是文本，具体的效果和对应关系在extra_event_core()里处理，如果需要随机等非对应性的逻辑也放extra_event_core()
				//第一层为步数
				0 => Array(
					'request' => '你不知道对方使用的是什么语言，但显然“它”设法让你明白，<span class="yellow b">你可以许下一个愿望</span>，而“它”能让其实现。<br>至于代价……<br>',
					
					'branches' => Array(
						1 => Array(
							'type' => 'button',
							'text' => '强大的力量',
							'hint' => '获得<:para1:>点基础攻击力和基础防御力，但是失去<:para2:>点生命上限',
						),
						2 => Array(
							'type' => 'button',
							'text' => '过人的技巧',
							'hint' => '获得技能『<:para3_skillname:>』，但是失去<:para4:>点经验值',
						),
						3 => Array(
							'type' => 'button',
							'text' => '庞大的财富',
							'hint' => '获得<:para5:>元钱，但是失去<:para6:>点体力上限',
						),
						99 => Array(
							'type' => 'button',
							'text' => '我不需要。',
							'hint' => '什么都不会发生',
						),
					),
					'results' => Array(
						1 => '“它”应允了。<br>你的身体变得比原来强壮得多，随之而来的是难以忍受的疼痛。<br>这就是代价吗？<br>',
						2 => '“它”应允了。<br>一段本不属于你的知识涌入你的大脑，覆盖了一部分你原有的记忆。<br>这就是代价吗？<br>',
						3 => '“它”应允了。<br>金钱从你的口袋里溢出来，但你感到阵阵疲倦，你意识到你预支了你未来的收获。<br>这就是代价吗？<br>',
						99 => '你拒绝了“它”的劝诱。<br>留下一股你难以理解的情感之后，“它”离去了。<br>这究竟是好是坏呢？<br>',
					)
				),
			),

			'expired' => '你来到与那个奇妙意识相遇的地方，但“它”已不知所踪。<br>',
		),
	);
}
?>