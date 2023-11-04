<?php

namespace song
{
	$songlist = array(
		1 => array(
			'songname' => 'Alicemagic',
			'noisekey' => 'ss_AM',
			'cost' => 30,
			'lyrics' => array(
				'♪ 你說過在哭泣之後應該可以破涕而笑 ♪',
				'♪ 我們的旅行　我不會忘 ♪',
				'♪ 施展魔法　為了不再失去　我不會說再見 ♪',
				'♪ 再次踏出腳步之時　將在某一天到來 ♪'
			),
			'effect' => array(
				'def' => 30
			),
			//effect_sv供ex_attr_song模块使用
			'effect_sv' => array(
				'def' => 200,
				'time' => 180,
			),
		),
		2 => array(
			'songname' => 'Crow Song',
			'noisekey' => 'ss_CS',
			'cost' => 90,
			'lyrics' => array(
				'♪ 从这里找一条路 ♪',
				'♪ 找到逃离的生路 ♪',
				'♪ 奏响激烈的摇滚 ♪',
				'♪ 盯紧遥远的彼方 ♪',
				'♪ 在这个连呼吸都难以为继的都市中 ♪'
			),
			'effect' => array(
				'att' => 30
			),
			'effect_sv' => array(
				'att' => 200,
				'time' => 180,
			),
		),
		3 => array(
			'songname' => 'KARMA',
			'noisekey' => 'KARMA',
			'cost' => 233,
			'lyrics' => array(
				'■'
			),
			'effect' => array(
				'rp' => '=0'
			),
			'effect_sv' => array(
				'rp' => '=-233'
			)
		),
		4 => array(
			'songname' => '驱寒颂歌',
			'noisekey' => 'ss_HWC',
			'cost' => 120,
			'lyrics' => array(
				'♪ 欢声笑语似天籁 ♪',
				'♪ 驱寒之夜又到来 ♪',
				'♪ 心中敞亮又欢快 ♪',
				'♪ 驱寒之夜又到来 ♪',
			),
			'lyrics_ruby' => array(
				'Ponies’ voices fill the night',
				'Hearth’s Warming Eve is here once again',
				'Happy hearts so full and bright',
				'Hearth’s Warming Eve is here once again',
			),
			'effect' => array(
				'mhp' => 10,
				'msp' => 10,
				'money' => 10,
				'rp' => -10
			),
			'effect_sv' => array(
				'mhp' => 40,
				'msp' => 20,
				'rp' => -10
			)
		),
		5 => array(
			'songname' => 'Butterfly',
			'noisekey' => 'ss_BF',
			'cost' => 600,
			'lyrics' => array(
				'♪ 無限大な夢のあとの ♪',
				'♪ 何もない世の中じゃ ♪',
				'♪ そうさ愛しい ♪',
				'♪ 想いも負けそうになるけど ♪',
			),
			'effect' => array(
				'weps' => '=∞',
				'hp' => array( 'e' =>2000, 'ignore_limit' => 1),
				'sp' => array( 'e' =>2000, 'ignore_limit' => 1),
			)
		), 
		6 => array(
			'songname' => '小苹果',
			'noisekey' => 'ss_XPG',
			'cost' => 10,
			'lyrics' => array(
				'♪ 你是我的小呀小苹果 ♪',
				'♪ 怎么爱你都不嫌多 ♪',
				'♪ 红红的小脸儿温暖我的心窝 ♪',
				'♪ 点亮我生命的火 火火火火~ ♪',
			),
			'effect' => array(
				'sp' => -100,
				'rage' => 5,
			),
			'effect_sv' => array(
				'sp' => -300,
				'rage' => 30,
			)
		),
		7 => array(
			'songname' => '空想神话',
			'noisekey' => 'ss_kuusou',
			'cost' => 'MAX',
			'lyrics' => array(
				'♪ 神の与えし 空想 Program ♪',
				'♪ さぁ eins zwei drei! 重なり合う ♪',
				'♪ さぁ eins zwei drei! 死を躱して ♪',
				'♪ 消灭の游戯に ♪',
				'♪ 焦がれる奇迹を夺う Suvival Game ♪',
			),
			'effect' => array(
				'special' => 1,
			)
		),
		8 => array(
			'songname' => 'ぼくのフレンド',
			'noisekey' => 'ss_friend',
			'cost' => 110,
			'lyrics' => array(
				'♪ 天赐奇缘 一生一见 ♪',
				'♪ 萍水相逢也是前世结缘 ♪',
				'♪ 把世上的奇迹聚到一起 ♪',
				'♪ 才得以与你相遇 ♪',
				
				'♪ 青春闭幕终有时 ♪',
				'♪ 哪怕随樱花飘散而去 ♪',
				'♪ 你我也必定有朝一日 ♪',
				'♪ 在别处再会 ♪',
				
				'♪ 致无可替代的 ♪',
				'♪ 与我相似的你 ♪',
				
				'♪ 哪怕独自摔倒 ♪',
				'♪ 伤痕累累 ♪',
				'♪ 无论何时何地 ♪',
				'♪ 都要奔跑向前 ♪',
				
				'♪ 偶尔生生气吵吵架吧 ♪',
				'♪ 看见哭丧着脸就好好安慰吧 ♪',
				'♪ 漫长的说教也请 ♪',
				'♪ 讲得简短些 ♪',
				
				'♪ 去寻找美好的东西吧 ♪',
				'♪ 多吃点好吃的东西吧 ♪',
				'♪ 换言之 今后也请 ♪',
				'♪ 多多关照啦 ♪',
			),
			'lyrics_ruby' => array(
				'合縁奇縁　一期一会',
				'袖すり合うも多生の縁',
				'この世の奇跡ギュッとつめて',
				'君と出会えたんだ',
				
				'青い春いつか幕を閉じ',
				'桜と共に舞い散っても',
				'必ず僕らまたどこかで',
				'出会いを果たすだろう',
				
				'かけがえない',
				'僕と似た君へ',
				
				'1人で転んで傷',
				'だらけになったときは',
				'いつでもどこ',
				'までも走るよ',
				
				'たまには喧嘩して怒ろう',
				'泣き顔見たら慰めよう',
				'とびきりの長いお説教は',
				'短めにして',
				
				'綺麗なものを探しに行こう',
				'美味しいものもたくさん食べよう',
				'つまりはこれからも',
				'どうかよろしくね',
			),
			'lyricdisp' => 2,
			'effect' => array(
				'addskill' => 498,
			)
		),
		9 => array(
			'songname' => '星めぐりの歌',
			'noisekey' => 'ss_planet',
			'cost' => 100,
			'lyrics' => array(
				'♪ 当猎户在天上高声歌唱时 ♪',
				'♪ 地上便降下了露水和冰霜 ♪',
				'♪ 仙女座中的云气 ♪',
				'♪ 宛如鱼的嘴形 ♪',
				'♪ 从大熊前脚向北 ♪',
				'♪ 延伸五倍之处 ♪',
				'♪ 在那小熊额顶 ♪',
				'♪ 正是巡星游天的枢轴 ♪',
			),
			'effect' => array(
				'wg' => 30,
				'wc' => 30,
			)
		),
		10 => array(
			'songname' => 'CANOE',
			'noisekey' => 'ss_rewrite',
			'cost' => 100,
			'lyrics' => array(
				'♪ 小小的木舟向着大海彼方远远驶去 ♪',
				'♪ 大大的船帆满满地承受着狂风 ♪',
				'♪ 驶向远方的崭新世界 ♪',
				'♪ 即便终焉之时再度来访 ♪',
				'♪ 我也想向你传达 ♪',
				'♪ 这漫长旅途的真正意义 ♪',
				'♪ “一切都是为了延续希望” ♪'
			),
			'effect' => array(
				'wd' => 30,
				'wf' => 30,
			)
		),
		11 => array(
			'songname' => '遥か彼方',
			'noisekey' => 'ss_lb',
			'cost' => 100,
			'lyrics' => array(
				'♪ 不论多遥远的彼方 ♪',
				'♪ 都有着旅途的终点 ♪',
				'♪ 但今天仍然要 向着彩虹的彼端进发 ♪',
				'♪ 只要你在欢笑 我也就会欢笑 ♪',
				'♪ 就会去迎接遥远夏日的那一天的到来 ♪'
			),
			'effect' => array(
				'wp' => 30,
				'wk' => 30,
			)
		),
		12 => array(
			'songname' => '雨だれの歌',
			'noisekey' => 'ss_amadare',
			'cost' => 60,
			'lyrics' => array(
				'♪ どこまでも歩いてく　君と手を繋ぎながら ♪',
				'♪ いつか辿り着いたその時は　共に笑えるように ♪',
				'♪ 始まりあれば　終わりも来るさ ♪',
				'♪ でも　また会えるはずさ ♪',
			),
			'effect' => array(
				'mhp' => 10,
				'hp' => 10,
				'msp' => 50,
				'sp' => 50,
			),
			'effect_sv' => array(
				'mhp' => 30,
				'hp' => 30,
				'msp' => 20,
				'sp' => 20,
			)
		),
		13 => array(
			'songname' => 'More One Night',
			'noisekey' => 'ss_mon',
			'cost' => 200,
			'lyrics' => array(
				'♪ 仅剩两人的这个世界 ♪',
				'♪ 今天也在转动 ♪',
				'♪ 今天明天还有后天 ♪',
				'♪ 我都能在你的身边吗？ ♪',
				'♪ 拜托了 ♪',
				'♪ 永不结束 ♪',
				'♪ 永不结束 ♪',
				'♪ 永不结束 ♪',
				'♪ 永不结束 ♪',
				'♪ 永不结束 ♪',
				'♪ 永不结束 ♪',
				'♪ 永不结束 ♪',
				'♪ 永不结束 ♪',
				'♪ 永不结束 ♪',
				'♪ 永不结束 ♪',
				'♪ 永不结束 ♪',
				'♪ 永不结束 ♪',
				'♪ 永不结束 ♪',
				'♪ 永不结束 ♪',
				'♪ 永不结束 ♪',
				'♪ 永不结束 ♪',//16次
			),
			'lyrics_ruby' => array(
				'ふたりぼっちの世界は',
				'本日も回っている',
				'今日も明日も明後日も',
				'キミの隣にいられるかな？',
				'お願い',
				'More one night',
				'More one night',
				'More one night',
				'More one night',
				'More one night',
				'More one night',
				'More one night',
				'More one night',
				'More one night',
				'More one night',
				'More one night',
				'More one night',
				'More one night',
				'More one night',
				'More one night',
				'More one night',
				'More one night',
				'More one night',
			),
			'lyricdisp' => 1,
			'effect' => array(
				'special' => 2,
				'addskill' => 501,
			)
		),
		14 => array(
			'songname' => 'Baba yetu',
			'noisekey' => 'ss_BY',
			'cost' => 88,
			'lyrics' => array(
				'♪ Baba yetu, yetu uliye ♪',
				'♪ Mbinguni yetu, yetu, Amina! ♪',
				'♪ Baba yetu, yetu, uliye ♪',
				'♪ ..Jina lako litukuzwe. ♪'
			),
			'effect' => array(
				'exp' => 12
			),
			'effect_sv' => array(
				'addskill' => 226,
				'time' => 120,
			)
		),
		15 => array(
			'songname' => 'Clear Morning',
			'noisekey' => 'ss_CM',
			'cost' => 100,
			'lyrics' => array(
				'♪ 荧光屏幕（Tap Tap）画面流转（Tic Tac） ♪',
				'♪ 映入视线（时间戳）已是早晨（一如既往） ♪',
				'♪ 令人欣喜的（话题）或是怦然心动的（好消息） ♪',
				'♪ 心灵的门扉（轻轻敲开）对着崭新的今日说（Hello!!） ♪'
			),
			'effect' => array(
				'hp' => array( 'e' =>1000, 'ignore_limit' => 1),
			)
		),
	);
	
	//聊天记录歌词显示条数
	$songchatlimit = 2;
	
	//没有注音时的歌词字体大小和行距
	$song_font_size = 14;
	$song_line_height = 16;
}
