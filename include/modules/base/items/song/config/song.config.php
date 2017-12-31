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
			)
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
			)
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
	);
	
	//聊天记录歌词显示条数
	$songchatlimit = 2;
	
	//没有注音时的歌词字体大小和行距
	$song_font_size = 14;
	$song_line_height = 16;
}