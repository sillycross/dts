<?php

namespace song
{
	$songlist = array(
		1 => array(
			'songname' => 'Alicemagic',
			'noisekey' => 'ss_AM',
			'cost' => 30,
			'lyrics' => array(
				'♪你說過在哭泣之後應該可以破涕而笑♪',
				'♪我們的旅行　我不會忘♪',
				'♪施展魔法　為了不再失去　我不會說再見♪',
				'♪再次踏出腳步之時　將在某一天到來♪'
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
				'♪从这里找一条路♪',
				'♪找到逃离的生路♪',
				'♪奏响激烈的摇滚♪',
				'♪盯紧遥远的彼方♪',
				'♪在这个连呼吸都难以为继的都市中♪'
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
				'♪欢声笑语似天籁♪',
				'♪驱寒之夜又到来♪',
				'♪心中敞亮又欢快♪',
				'♪驱寒之夜又到来♪',
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
				'♪無限大な夢のあとの♪',
				'♪何もない世の中じゃ♪',
				'♪そうさ愛しい♪',
				'♪想いも負けそうになるけど♪',
			),
			'effect' => array(
				'weps' => '=∞',
				'hp' => 5000,
				'sp' => 5000,
			)
		),
		6 => array(
			'songname' => '小苹果',
			'noisekey' => 'ss_XPG',
			'cost' => 10,
			'lyrics' => array(
				'♪你是我的小呀小苹果♪',
				'♪怎么爱你都不嫌多♪',
				'♪红红的小脸儿温暖我的心窝♪',
				'♪点亮我生命的火 火火火火~♪',
			),
			'effect' => array(
				'sp' => -100,
				'rage' => 5,
			)
		),
		
	);
	
	//聊天记录歌词显示条数
	$songchatlimit = 2;
	
	//没有注音时的歌词字体大小和行距
	$song_font_size = 14;
	$song_line_height = 16;
}