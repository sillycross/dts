<?php
namespace cardbase{
$cardtypecd=array(//卡片类别CD，单位秒
	'S' => 43200,
	'A' => 7200,
	'B' => 3600,
	'C' => 0,
	'M' => 0
);
$packlist=array(
	'Standard Pack',
	'Crimson Swear',
	'Way of Life',
	'Best DOTO',
	'Balefire Rekindle',
	'Way of Life Game',
	'Event Bonus'
);
$packdesc = array(
	'Standard Pack' => '最早登场的卡集，大杂烩。卡片内容多是游戏开发者、玩家和当时的一些梗。',
	'Crimson Swear' => '以游戏阵营「红杀」组织以及其马甲「金龙通讯社」为主题的卡集。',
	'Way of Life' => '以游戏开发者、重要玩家、有代表性的游戏方式以及同类游戏为捏他对象的卡集。',
	'Best DOTO' => '以电竞元素和电竞圈为吐槽对象的卡集。',
	'Balefire Rekindle' => '以游戏版本「复燃」的新增NPC角色和游戏设定为主题的卡集。',
	'Way of Life Game' => '第二弹以游戏元素为主要吐槽点的卡集，也夹杂有少量其他要素。',
	'Event Bonus' => '其他一些零散成就和活动奖励卡。'
);
$packstart = array(
	'Way of Life Game' => 1541905871
);
$cardindex=array(
	'S'=>array(1,5,16,38,39,40,41,64,65,67,71,95,99,100,101,102,117,145,152,153),
	'A'=>array(2,13,14,20,22,23,26,27,32,37,43,44,45,46,47,48,49,50,68,72,75,81,103,104,105,106,120,121,124,135,136,137,139,141,148,154),
	'B'=>array(3,12,15,21,24,25,28,35,51,52,53,54,55,56,66,69,70,76,78,80,83,97,108,109,110,111,112,123,140,142,144,146,147,149,157,161,163,164),
	'C'=>array(4,6,7,8,9,10,11,17,18,19,29,30,31,33,34,36,57,58,59,60,61,62,73,74,77,79,82,84,85,107,113,114,115,116,122,138,143,150,155,162,210),
	'M'=>array()
	//M卡的爆率实际属于C
	//应四面要求，pop子这张卡虽然设定为S卡，但爆率是C卡的爆率
);
$card_rarecolor=array(
	'S'=>'gold b ',
	'A'=>'clan ',
	'B'=>'brickred b ',
	'C'=>'white ',
	'M'=>'grey '
);
$card_rarity_html = array(
	'S'=>'<span class="'.$card_rarecolor['S'].'">S</span>',
	'A'=>'<span class="'.$card_rarecolor['A'].'">A</span>',
	'B'=>'<span class="'.$card_rarecolor['B'].'">B</span>',
	'C'=>'<span class="'.$card_rarecolor['C'].'">C</span>',
	'M'=>'<span class="'.$card_rarecolor['M'].'">M</span>'
);	
//卡片返回切糕的价格
$card_price = array(
	'S'=>499,
	'A'=>99,
	'B'=>49,
	'C'=>30,
	'M'=>99
);
$cards = array(
	0 => array(
		'name' => '挑战者',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '默认的身份卡',
		'effect' => '无',
		'energy' => 0,
		'valid' => array(
			'pls' => '0',
		)
	),
	1 => array(
		'name' => '残留的思念',
		'rare' => 'S',
		'pack' => 'Standard Pack',
		'desc' => '镇守键刃墓场的BOSS',
		'effect' => '开局经验固定为120点',
		'energy' => 100,
		'valid' => array(
			'exp' => '120',
		)
	),
	2 => array(
		'name' => '初音大魔王',
		'rare' => 'A',
		'pack' => 'Standard Pack',
		'desc' => '2011年愚人节<br>宇宙巡航机活动的优胜者',
		'effect' => '开局装备强力的射系武器',
		'energy' => 120,
		'valid' => array(
			'wep' => '「Falchion Rider」',
			'wepk' => 'WG',
			'wepe' => '76',
			'weps' => '21',
			'wepsk' => 'ur',
		)
	),
	3 => array(
		'name' => '面糊饼职人',
		'rare' => 'B',
		'pack' => 'Standard Pack',
		'desc' => '善于制作面糊饼的著名面点师傅',
		'effect' => '强化开局补给',
		'energy' => 80,
		'valid' => array(
			'itm1' => '面糊饼',
			'itmk1' => 'HB',
			'itme1' => '120',
			'itmsk1' => 'z',
			'itm2' => '面糊汤',
			'itmk2' => 'HB',
			'itme2' => '120',
			'itmsk2' => 'z',
		)
	),
	4 => array(
		'name' => 'AC专业职人',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '死后会召唤强力NPC的特殊小兵',
		'effect' => '开局全熟+5',
		'energy' => 0,
		'valid' => array(
			'wp' => '5',
			'wk' => '5',
			'wc' => '5',
			'wg' => '5',
			'wf' => '5',
			'wd' => '5',
		)
	),
	5 => array(
		'name' => '虚子',
		'rare' => 'S',
		'pack' => 'Standard Pack',
		'desc' => '这个小卡片系统的作者，<br>爱好是红暮和加强斩系',
		'effect' => '可选称号里必然有见敌必斩和黑衣组织；斩系技能强化；开局装备寻星者',
		'energy' => 150,
		'valid' => array(
			'skills' => array('484'=>'0'),
			'wep' => '『寻星者』',
			'wepk' => 'WK',
			'wepe' => '90',
			'weps' => '35',
			'wepsk' => 'd',
		)
	),
	6 => array(
		'name' => '殴系触手',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '熟练的殴系玩家',
		'effect' => '称号固定为街头霸王',
		'energy' => 0,
		'valid' => array(
			'club' => '1',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'13' => '0', 
				'32' => '0', 
				'34' => '0', 
				'35' => '0', 
				'36' => '0', 
				'37' => '0', 
				'38' => '0', 
			),
		)
	),
	7 => array(
		'name' => '斩系触手',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '熟练的斩系玩家',
		'effect' => '称号固定为见敌必斩',
		'energy' => 0,
		'valid' => array(
			'club' => '2',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'14' => '0', 
				'82' => '0', 
				'207' => '0', 
				'208' => '0', 
				'209' => '0', 
				'210' => '0', 
			),
		)
	),
	8 => array(
		'name' => '投系触手',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '熟练的投系玩家',
		'effect' => '称号固定为灌篮高手',
		'energy' => 0,
		'valid' => array(
			'club' => '4',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'16' => '0', 
				'48' => '0', 
				'49' => '0', 
				'50' => '0', 
				'51' => '0', 
				'273' => '0',
				'52' => '0', 
			),
		)
	),
	9 => array(
		'name' => '射系触手',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '熟练的射系玩家',
		'effect' => '称号固定为狙击鹰眼',
		'energy' => 0,
		'valid' => array(
			'club' => '3',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'15' => '0', 
				'201' => '0', 
				'202' => '0', 
				'203' => '0', 
				'204' => '0', 
				'205' => '0', 
				'265' => '0', 
				'206' => '0', 
			),
		)
	),
	10 => array(
		'name' => '灵系触手',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '熟练的灵系玩家',
		'effect' => '称号固定为超能力者',
		'energy' => 0,
		'valid' => array(
			'club' => '9',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'18' => '0', 
				'65' => '0', 
				'73' => '0', 
				'76' => '0', 
				'74' => '0', 
			),
		)
	),
	11 => array(
		'name' => '爆系触手',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '熟练的爆系玩家',
		'effect' => '称号固定为拆弹专家',
		'energy' => 0,
		'valid' => array(
			'club' => '5',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'17' => '0', 
				'19' => '0', 
				'20' => '0', 
				'211' => '0', 
				'212' => '0', 
				'213' => '0', 
				'214' => '0', 
				'215' => '0', 
				'216' => '0', 
			),
		)
	),
	12 => array(
		'name' => '姜瘤儿',
		'rare' => 'B',
		'pack' => 'Standard Pack',
		'desc' => '一名头很硬的著名游戏玩家',
		'effect' => '获得技能「重击1」「硬化1」',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'400' => '1', 
				'401' => '1', 
			),
		)
	),
	13 => array(
		'name' => '熊本熊',
		'rare' => 'A',
		'pack' => 'Standard Pack',
		'desc' => '日本熊本县的吉祥物，<br>和大逃杀没有任何关系',
		'effect' => '获得技能「直死1」',
		'energy' => 120,
		'valid' => array(
			'skills' => array(
				'402' => '1', 
			),
		)
	),
	14 => array(
		'name' => '贝尔格里尔斯',
		'rare' => 'A',
		'pack' => 'Standard Pack',
		'desc' => '该介绍已被吃掉',
		'effect' => '可把任何物品当作补给食用<br>有毒补给对你视为无毒',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'458' => '0', 
			),
		)
	),
	15 => array(
		'name' => '常威',
		'rare' => 'B',
		'pack' => 'Standard Pack',
		'desc' => '我是天生神功',
		'effect' => '获得高速成长技能「神功」',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'229' => '0', 
			),
		)
	),
	16 => array(
		'name' => 'tabris',
		'rare' => 'S',
		'pack' => 'Standard Pack',
		'desc' => '经常死于不明AOE的顽强神触',
		'effect' => '获得技能「重击3」「硬化3」，但踩雷率提高',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'22' => '0', 
				'400' => '3', 
				'401' => '3', 
			),
		)
	),
	17 => array(
		'name' => '沙包长者',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '身经百战见的多了……的沙包',
		'effect' => '开局获得3个技能点',
		'energy' => 0,
		'valid' => array(
			'skillpoint' => '3',
		)
	),
	18 => array(
		'name' => '业务员',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '穿着正式骨骼精奇的白领',
		'effect' => '开局装备完整的防具',
		'energy' => 0,
		'valid' => array(
			'arb' => '西服',
			'arbk' => 'DB',
			'arbe' => '45',
			'arbs' => '30',
			'arbsk' => '',
			'arh' => '眼镜',
			'arhk' => 'DH',
			'arhe' => '45',
			'arhs' => '30',
			'arhsk' => '',
			'arf' => '西裤',
			'arfk' => 'DF',
			'arfe' => '45',
			'arfs' => '30',
			'arfsk' => '',
			'ara' => '手表',
			'arak' => 'DA',
			'arae' => '45',
			'aras' => '30',
			'arask' => '',
		)
	),
	19 => array(
		'name' => '铁男',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '为什么要放铁男？',
		'effect' => '获得技能「重击1」',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'400' => '1', 
			),
		)
	),
	20 => array(
		'name' => '十万只脚本狗',
		'rare' => 'A',
		'pack' => 'Standard Pack',
		'desc' => 'SC出品的<br>一秒钟能合两把A刀的恐怖AI',
		'effect' => '获得技能「追击1」，但踩雷率提高',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'22' => '0', 
				'403' => '1', 
			),
		)
	),
	21 => array(
		'name' => '暗杀者',
		'rare' => 'B',
		'pack' => 'Standard Pack',
		'desc' => '熟练的黑衣组织玩家',
		'effect' => '称号固定为黑衣组织',
		'energy' => 100,
		'valid' => array(
			'club' => '8',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'219' => '0', 
				'220' => '0', 
				'217' => '0', 
				'218' => '0', 
				'221' => '0', 
				'222' => '0', 
				'223' => '0', 
				'224' => '0', 
			),
		)
	),
	22 => array(
		'name' => '霸道总裁喵',
		'rare' => 'A',
		'pack' => 'Standard Pack',
		'desc' => '熟练的富家子弟玩家',
		'effect' => '称号固定为富家子弟',
		'energy' => 250,
		'valid' => array(
			'club' => '11',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'69' => '0', 
				'68' => '0', 
				'55' => '0', 
				'67' => '0', 
				'56' => '0', 
				'66' => '0', 
			),
		)
	),
	23 => array(
		'name' => '宇宙神触',
		'rare' => 'A',
		'pack' => 'Standard Pack',
		'desc' => '熟练的高速成长玩家',
		'effect' => '称号固定为高速成长',
		'energy' => 80,
		'valid' => array(
			'club' => '10',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'78' => '0', 
				'225' => '0', 
				'226' => '0', 
				'270' => '0', 
				'229' => '0', 
				'271' => '0', 
				
				'228' => '0', 
			),
		)
	),
	24 => array(
		'name' => '无解肥',
		'rare' => 'B',
		'pack' => 'Standard Pack',
		'desc' => '熟练的根性兄贵玩家',
		'effect' => '称号固定为根性兄贵',
		'energy' => 100,
		'valid' => array(
			'club' => '13',
			'skills' => array(
				'29' => '0', 
				'11' => '0', 
				'12' => '0', 
				'31' => '0', 
				'28' => '0', 
				'30' => '0', 
				'267' => '0', 
				'268' => '0',
				'269' => '0',
			),
		)
	),
	25 => array(
		'name' => '三国杀高玩',
		'rare' => 'B',
		'pack' => 'Standard Pack',
		'desc' => '熟练的肌肉兄贵玩家',
		'effect' => '称号固定为肌肉兄贵',
		'energy' => 100,
		'valid' => array(
			'club' => '14',
			'skills' => array(
				'10' => '0', 
				'39' => '0', 
				'12' => '0', 
				'79' => '0',
				'44' => '0', 
				'40' => '0', 
				'45' => '0', 
				'43' => '0', 
				'41' => '0', 
				'42' => '0', 
				'46' => '0', 
			),
		)
	),
	26 => array(
		'name' => '全能骑士',
		'rare' => 'A',
		'pack' => 'Standard Pack',
		'desc' => '熟练的天赋异禀玩家',
		'effect' => '称号固定为天赋异禀',
		'energy' => 100,
		'valid' => array(
			'club' => '18',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'70' => '0', 
				'72' => '0', 
				'71' => '0', 
				'77' => '0', 
			),
		)
	),
	27 => array(
		'name' => '黑锋精英',
		'rare' => 'A',
		'pack' => 'Standard Pack',
		'desc' => '熟练的亡灵骑士玩家',
		'effect' => '称号固定为亡灵骑士',
		'energy' => 100,
		'valid' => array(
			'club' => '24',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'24' => '0', 
				'58' => '0', 
				'61' => '0', 
				'62' => '0', 
				'60' => '0', 
				'64' => '0', 
				'63' => '0', 
				'59' => '0', 
			),
		)
	),
	28 => array(
		'name' => '夏之岛住民',
		'rare' => 'B',
		'pack' => 'Standard Pack',
		'desc' => '熟练的宝石骑士玩家',
		'effect' => '称号固定为宝石骑士',
		'energy' => 100,
		'valid' => array(
			'club' => '20',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'23' => '0', 
				'27' => '0', 
				'26' => '0',
				'24' => '0', 
				'54' => '0', 
				'25' => '0', 
				'272' => '0', 
			),
		)
	),
	29 => array(
		'name' => '富二代',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '其实他也不是特别有钱',
		'effect' => '起始金钱为100',
		'energy' => 0,
		'valid' => array(
			'money' => '100',
		)
	),
	30 => array(
		'name' => '穆里尼奥',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '和全世界为敌的男人',
		'effect' => '起始怒气为100',
		'energy' => 0,
		'valid' => array(
			'rage' => '100',
		)
	),
	31 => array(
		'name' => '变态',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '单纯的变态',
		'effect' => '？？？',
		'energy' => 0,
		'valid' => array(
			'arb' => '女生校服',
			'arbk' => 'DB',
			'arbe' => '5',
			'arbs' => '15',
			'arbsk' => '',
			'gd' => 'm',
			'icon' => '9',
		)
	),
	32 => array(
		'name' => 'BurNIng',
		'rare' => 'A',
		'pack' => 'Standard Pack',
		'desc' => '这个人用鸟习惯特别不好',
		'effect' => '自带雷达',
		'energy' => 80,
		'valid' => array(
			'itm3' => '雷达',
			'itmk3' => 'ER',
			'itme3' => '44',
			'itms3' => '44',
			'itmsk3' => '2',
		)
	),
	33 => array(
		'name' => '拳法家',
		'rare' => 'M',
		'pack' => 'Standard Pack',
		'desc' => '孤高的拳法家，<br>对各种小伎俩不屑一顾',
		'effect' => '拳法家不需要多余的技能',
		'energy' => 0,
		'valid' => array(
			'club' => '14',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
			),
			'hp' => '450',
			'mhp' => '450',
			'wp' => '50',
			'att' => '150',
		)
	),
	34 => array(
		'name' => '草药学家',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '药学家是什么人，<br>为什么要伤害他们？',
		'effect' => '可以查毒；下毒造成伤害x2',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'220' => '0', 
			),
		)
	),
	35 => array(
		'name' => '2009',
		'rare' => 'B',
		'pack' => 'Standard Pack',
		'desc' => '著名的生物学家，兼任创世神',
		'effect' => '可以召唤保安',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'56' => '0', 
			),
		)
	),
	36 => array(
		'name' => '残疾人',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '由于不明原因只有一只手的残疾人',
		'effect' => '为什么他只有一只手呢',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'68' => '0', 
			),
		)
	),
	37 => array(
		'name' => '华莱士',
		'rare' => 'A',
		'pack' => 'Standard Pack',
		'desc' => '美国著名新闻工作者',
		'effect' => '初始属性不知道高到哪里去了',
		'energy' => 150,
		'valid' => array(
			'hp' => '450',
			'mhp' => '450',
			'sp' => '450',
			'msp' => '450',
			'att' => '150',
			'def' => '150',
			'money' => '50',
			'wp' => '50',
			'wk' => '50',
			'wc' => '50',
			'wg' => '50',
			'wd' => '50',
			'wf' => '50',
		)
	),
	38 => array(
		'name' => '『芙蓉（Fleur）』',
		'title' => '『芙蓉』',
		'rare' => 'S',
		'pack' => 'Crimson Swear',
		'desc' => '低调行事的年轻女性，<br>红暮的青梅竹马。<br>目前是红暮的影一样的存在。<br>担当红杀组织中并不存在的<br>隐秘行动课程的教头。',
		'effect' => '被发现率下降，爆系和斩系伤害提高',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'405' => '0', 
			),
		)
	),
	39 => array(
		'name' => '『红暮（Crimson）』',
		'title' => '『红暮』',
		'rare' => 'S',
		'pack' => 'Crimson Swear',
		'desc' => '英姿飒爽的年轻女性。<br>表面上是城内世家的千金，以及湾城最大的实业『金龙通讯社』的CEO，<br>实际是佣兵组织『红杀』的现任当家',
		'effect' => '战斗中60%概率发动重击',
		'energy' => 180,
		'valid' => array(
			'skills' => array(
				'400' => '5', 
			),
		)
	),
	40 => array(
		'name' => '『蓝凝（Azure）』',
		'title' => '『蓝凝』',
		'rare' => 'S',
		'pack' => 'Crimson Swear',
		'desc' => '<span class="clan">“蓝凝我觉得啊，<br>这个地方没什么好写的。<br>总之我比红暮可强得多了，<br>哈哈哈！”</span>',
		'effect' => '<span class="clan">“蓝凝觉得你进游戏实际体验一下<br>比较好哦！”</span>',
		'energy' => 90,
		'valid' => array(
			'hp' => '260',
			'mhp' => '260',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'406' => '0', 
			),
			'club' => '17',
		)
	),
	41 => array(
		'name' => '『丁香（Lila）』',
		'title' => '『丁香』',
		'rare' => 'S',
		'pack' => 'Crimson Swear',
		'desc' => '芙蓉的妹妹，现年初二，<br>在一般的平民初中就读。是学校演剧部的部长，也备有无数的戏服用品。<br>爱好是写剧本和读其他人的剧本',
		'effect' => '根据当前HP百分比提高攻击力或防御力',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'407' => '0', 
			),
		)
	),
	42 => array(
		'name' => 'Tianming "Fleur" Li',
		'title' => '"Fleur"',
		'rare' => 'S',
		'pack' => 'Event Bonus',
		'desc' => '低调行事的年轻女性，<br>红暮的青梅竹马。<br>目前是红暮的影一样的存在。<br>担当红杀组织中并不存在的<br>隐秘行动课程的教头。',
		'effect' => '被发现率下降，爆系和斩系伤害提高',
		'energy' => 50,
		'valid' => array(
			'skills' => array(
				'405' => '0', 
			),
		)
	),
	43 => array(
		'name' => '『飞龙（Wyvern）』',
		'title' => '『飞龙』',
		'rare' => 'A',
		'pack' => 'Crimson Swear',
		'desc' => '红暮和蓝凝的爷爷。<br>前代红杀将军。<br>在二人的父亲『幻铁』行踪不明后，抚养二人长大。目前隐居在城外的乡村中卖中药为生。',
		'effect' => '减少受到的战斗伤害和陷阱伤害',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'409' => '0', 
			),
		)
	),
	44 => array(
		'name' => '『翼虎（Manticore）』',
		'title' => '『翼虎』',
		'rare' => 'A',
		'pack' => 'Crimson Swear',
		'desc' => '『飞龙』的好友。<br>前代红杀菁英。<br>据说只要他的盾还在身上，<br>没什么东西能伤得了他。<br>目前他的盾由红暮收藏，他自己则在飞龙之前就已经退役了。',
		'effect' => '探索体力消耗-12，战斗怒气获得+4',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'410' => '0', 
			),
		)
	),
	45 => array(
		'name' => '『铁城（Rook）』',
		'title' => '『铁城』',
		'rare' => 'A',
		'pack' => 'Crimson Swear',
		'desc' => '红杀的拳脚教头',
		'effect' => '殴系伤害+25%，拳头伤害+40%',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'408' => '1', 
			),
		)
	),
	46 => array(
		'name' => '『灵翼（Bishop）』',
		'title' => '『灵翼』',
		'rare' => 'A',
		'pack' => 'Crimson Swear',
		'desc' => '红杀的火器教头',
		'effect' => '远程兵器伤害+20%',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'408' => '2', 
			),
		)
	),
	47 => array(
		'name' => '『破石（Knight）』',
		'title' => '『破石』',
		'rare' => 'A',
		'pack' => 'Crimson Swear',
		'desc' => '红杀的冷兵器教头',
		'effect' => '斩系伤害+20%，投系伤害+15%',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'408' => '3', 
			),
		)
	),
	48 => array(
		'name' => '『银锤（Pawn）』',
		'title' => '『银锤』',
		'rare' => 'A',
		'pack' => 'Crimson Swear',
		'desc' => '红杀的爆炸物教头',
		'effect' => '爆系伤害+15%',
		'energy' => 80,
		'valid' => array(
			'skills' => array(
				'408' => '4', 
			),
		)
	),
	49 => array(
		'name' => '『电返（King）』',
		'title' => '『电返』',
		'rare' => 'A',
		'pack' => 'Crimson Swear',
		'desc' => '红杀的信息技术教头',
		'effect' => '升级时获得2-4点全熟练',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'411' => '0', 
			),
		)
	),
	50 => array(
		'name' => '『三步（Queen）』',
		'title' => '『三步』',
		'rare' => 'A',
		'pack' => 'Crimson Swear',
		'desc' => '红杀的轻功体能教头',
		'effect' => '战斗中对手的射程越远，造成的伤害就越高',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'408' => '5', 
			),
		)
	),
	51 => array(
		'name' => '红杀特工P',
		'rare' => 'B',
		'pack' => 'Crimson Swear',
		'desc' => '善于肉搏的红杀特工',
		'effect' => '开局殴熟+35',
		'energy' => 100,
		'valid' => array(
			'wp' => '35',
		)
	),
	52 => array(
		'name' => '红杀特工K',
		'rare' => 'B',
		'pack' => 'Crimson Swear',
		'desc' => '善于使用冷兵器的红杀特工',
		'effect' => '开局斩熟+35',
		'energy' => 100,
		'valid' => array(
			'wk' => '35',
		)
	),
	53 => array(
		'name' => '红杀特工C',
		'rare' => 'B',
		'pack' => 'Crimson Swear',
		'desc' => '善于使用飞行道具的红杀特工',
		'effect' => '开局投熟+35',
		'energy' => 100,
		'valid' => array(
			'wc' => '35',
		)
	),
	54 => array(
		'name' => '红杀特工G',
		'rare' => 'B',
		'pack' => 'Crimson Swear',
		'desc' => '善于使用枪械的红杀特工',
		'effect' => '开局射熟+35',
		'energy' => 100,
		'valid' => array(
			'wg' => '35',
		)
	),
	55 => array(
		'name' => '红杀特工D',
		'rare' => 'B',
		'pack' => 'Crimson Swear',
		'desc' => '善于使用爆炸物的红杀特工',
		'effect' => '开局爆熟+35',
		'energy' => 100,
		'valid' => array(
			'wd' => '35',
		)
	),
	56 => array(
		'name' => '红杀特工A',
		'rare' => 'B',
		'pack' => 'Crimson Swear',
		'desc' => '战斗风格多变的红杀特工',
		'effect' => '开局灵熟以外的熟练度+15',
		'energy' => 100,
		'valid' => array(
			'wp' => '15',
			'wk' => '15',
			'wc' => '15',
			'wg' => '15',
			'wd' => '15',
		)
	),
	57 => array(
		'name' => '书卷使卡玛',
		'title' => '书卷使',
		'rare' => 'C',
		'pack' => 'Crimson Swear',
		'desc' => '在时空特使里默默无闻打工的<br>工作人员。某次事件之后就消失了',
		'effect' => '开局攻防为115',
		'energy' => 0,
		'valid' => array(
			'att' => '115',
			'def' => '115',
		)
	),
	58 => array(
		'name' => 'G.D.S 项目经理',
		'rare' => 'C',
		'pack' => 'Crimson Swear',
		'desc' => '他因为乱改需求已经被人盯上了',
		'effect' => '可以用改需求折磨别人',
		'energy' => 0,
		'valid' => array(
			'wep' => '需求清单',
			'wepk' => 'WF',
			'wepe' => '66',
			'weps' => '44',
			'wepsk' => 'd',
		)
	),
	59 => array(
		'name' => 'G.D.S 文员',
		'rare' => 'C',
		'pack' => 'Crimson Swear',
		'desc' => '因为长期加班导致谢顶的中年男人',
		'effect' => '他准备了很多提神的饮料',
		'energy' => 0,
		'valid' => array(
			'itm3' => '红牛',
			'itmk3' => 'Ca',
			'itme3' => '1',
			'itms3' => '10',
			'itmsk3' => 'Z',
		)
	),
	60 => array(
		'name' => 'G.D.S 保安',
		'rare' => 'C',
		'pack' => 'Crimson Swear',
		'desc' => '他一般没有什么出手的机会，<br>所以锻炼方向有点走偏',
		'effect' => '初始攻击大幅降低，防御大幅提高',
		'energy' => 0,
		'valid' => array(
			'att' => '15',
			'def' => '155',
		)
	),
	61 => array(
		'name' => 'G.D.S 领导',
		'rare' => 'M',
		'pack' => 'Crimson Swear',
		'desc' => '由于长期坐办公室，<br>他的身材已经严重走形了',
		'effect' => '初始经验值为65，但体力大幅下降',
		'energy' => 0,
		'valid' => array(
			'sp' => '40',
			'msp' => '40',
			'exp' => '65',
		)
	),
	62 => array(
		'name' => 'G.D.S 扫地大妈',
		'rare' => 'C',
		'pack' => 'Crimson Swear',
		'desc' => '金龙通讯社的第一道坚强防线，在公司的日常运转中也发挥着极大的作用',
		'effect' => '获得技能「人杰」',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'66' => '0', 
			),
		)
	),
	63 => array(
		'name' => '"Sexycoder"',
		'rare' => 'S',
		'pack' => 'Event Bonus',
		'desc' => '不愿透露姓名的究极神牛，<br>代码力深不可测',
		'effect' => '称号固定为锡安成员，技能「过载」大幅强化，且开局即解锁',
		'energy' => 80,
		'valid' => array(
			'club' => '7',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'230' => '0', 
				'233' => '0', 
				'235' => '0', 
				'231' => '0', 
				'236' => '0', 
				'232' => '0', 
				'237' => '0', 
				'238' => '0', 
				'234' => '0', 
			),
		)
	),
	64 => array(
		'name' => '"Topcoder"',
		'rare' => 'S',
		'pack' => 'Way of Life',
		'desc' => '不愿透露姓名的究极神牛，<br>代码力深不可测',
		'effect' => '称号固定为锡安成员，技能「过载」强化，且开局即解锁',
		'energy' => 100,
		'valid' => array(
			'club' => '7',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'230' => '0', 
				'233' => '0', 
				'235' => '0', 
				'231' => '0', 
				'236' => '0', 
				'232' => '0', 
				'237' => '0', 
				'238' => '0', 
				'234' => '0', 
			),
		)
	),
	65 => array(
		'name' => '"KHIBIKI《黑曲》"',
		'title' => '『黑曲』',
		'rare' => 'S',
		'pack' => 'Way of Life',
		'desc' => 'ACFUN大逃杀画师，游戏中的萌妹子头像和UI都出自她手',
		'effect' => '减半偶数战斗伤害，直至其为奇数',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'415' => '0', 
			),
		)
	),
	66 => array(
		'name' => '"KEY男"',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '经常出没在清水池的神秘人物',
		'effect' => '合成KEY系武器时耐久度增加',
		'energy' => 125,
		'valid' => array(
			'skills' => array(
				'413' => '0', 
			),
		)
	),
	67 => array(
		'name' => '霜火协奏曲',
		'rare' => 'S',
		'pack' => 'Way of Life',
		'desc' => 'ACFUN大逃杀史上第一神触',
		'effect' => '战斗中获得的熟练度+1',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'496' => '0', 
			),
		)
	),
	68 => array(
		'name' => '狂信徒',
		'rare' => 'A',
		'pack' => 'Way of Life',
		'desc' => '大家都很讨厌他，<br>他自己却没什么自觉',
		'effect' => '开局携带【风神的神德】',
		'energy' => 100,
		'valid' => array(
			'itm3' => '【风神的神德】',
			'itmk3' => 'WF',
			'itme3' => '233',
			'itms3' => '10',
			'itmsk3' => 'z',
		)
	),
	69 => array(
		'name' => '活雷锋',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '一个有益于人民的人',
		'effect' => '开局携带驱云弹',
		'energy' => 200,
		'valid' => array(
			'itm3' => '驱云弹',
			'itmk3' => 'EW',
			'itme3' => '1',
			'itms3' => '1',
			'itmsk3' => '1',
		)
	),
	70 => array(
		'name' => '情怀挑战者',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '他的精神寄托就是这把刀了',
		'effect' => '开局装备火焰属性的斩系武器',
		'energy' => 100,
		'valid' => array(
			'wep' => '飞龙刀【紅葉】',
			'wepk' => 'WK',
			'wepe' => '80',
			'weps' => '40',
			'wepsk' => 'uc',
		)
	),
	71 => array(
		'name' => '枪毙的某神',
		'rare' => 'S',
		'pack' => 'Way of Life',
		'desc' => '著名的小黄系列玩家，设计了《小黄的大师球》和初版游戏王的合成',
		'effect' => '获得一段时间内必中（对某些武器无效）的技能',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'414' => '0', 
			),
		)
	),
	72 => array(
		'name' => '方块挑战者',
		'rare' => 'A',
		'pack' => 'Way of Life',
		'desc' => '猜猜他能上几个属性',
		'effect' => '每隔一段时间可以生成一个方块',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'420' => '0', 
			),
		)
	),
	73 => array(
		'name' => '苹果姐姐',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '唉',
		'effect' => '你知道的',
		'energy' => 0,
		'valid' => array(
			'arf' => '灯笼裤',
			'arfk' => 'DF',
			'arfe' => '140',
			'arfs' => '30',
			'arfsk' => '',
		)
	),
	74 => array(
		'name' => 'BR挑战者',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '在严苛的原版BR环境中<br>生存下来的玩家',
		'effect' => '初始生命+300，体力+300',
		'energy' => 0,
		'valid' => array(
			'hp' => '700',
			'sp' => '700',
		)
	),
	75 => array(
		'name' => '精锐挑战者',
		'rare' => 'A',
		'pack' => 'Way of Life',
		'desc' => '不管怎么看，这都是一位标准的触手',
		'effect' => '初始装备与思念体-触手众相同',
		'energy' => 100,
		'valid' => array(
			'wep' => '触手的力量',
			'wepk' => 'WG',
			'wepe' => '75',
			'weps' => '200',
			'wepsk' => '',
			'arb' => 'SSS战队校服',
			'arbk' => 'DB',
			'arbe' => '150',
			'arbs' => '100',
			'arbsk' => '',
			'arh' => '鼓舞士气的头带',
			'arhk' => 'DH',
			'arhe' => '75',
			'arhs' => '100',
			'arhsk' => '',
			'arf' => '广播装置手表α',
			'arfk' => 'DF',
			'arfe' => '75',
			'arfs' => '100',
			'arfsk' => '',
			'ara' => '橙黄学生鞋',
			'arak' => 'DA',
			'arae' => '75',
			'aras' => '100',
			'arask' => '',
		)
	),
	76 => array(
		'name' => '时空挑战者',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '精通查危险、移NPC、刷局、隐藏合成等各种高级PVE技巧的时空服玩家',
		'effect' => '对玩家伤害-50%，对NPC伤害+30%',
		'energy' => 150,
		'valid' => array(
			'skills' => array(
				'416' => '0', 
			),
		)
	),
	77 => array(
		'name' => '电波挑战者',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '为了胜利不择手段的电波服玩家',
		'effect' => '对玩家伤害+10%，对NPC伤害-10%',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'416' => '1', 
			),
		)
	),
	78 => array(
		'name' => '死斗挑战者',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '史上最快死斗缔造者',
		'effect' => '开局携带杏仁豆腐的ID卡',
		'energy' => 100,
		'valid' => array(
			'itm3' => '杏仁豆腐的ID卡模样的杏仁豆腐',
			'itmk3' => 'HB',
			'itme3' => '77',
			'itms3' => '77',
			'itmsk3' => 'Z',
		)
	),
	79 => array(
		'name' => '春原挑战者',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '来自春原服的新人玩家',
		'effect' => '最大生命值大幅提高，但是无法选择称号',
		'energy' => 0,
		'valid' => array(
			'hp' => '800',
			'mhp' => '800',
			'club' => '17',
		)
	),
	80 => array(
		'name' => '兵马俑',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '熟练的西安成员玩家',
		'effect' => '称号固定为锡安成员',
		'energy' => 100,
		'valid' => array(
			'club' => '7',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'230' => '0', 
				'233' => '0', 
				'235' => '0', 
				'231' => '0', 
				'236' => '0', 
				'232' => '0', 
				'237' => '0', 
				'238' => '0', 
				'234' => '0', 
			),
		)
	),
	81 => array(
		'name' => '篝火挑战者',
		'rare' => 'A',
		'pack' => 'Way of Life',
		'desc' => '来自早已消亡的篝火服的神秘玩家',
		'effect' => '随机发动一张身份卡的效果<br>S:20% A:40% B:20% C:20%',
		'energy' => 100,
		'valid' => array(
			'pls' => '0',
		)
	),
	82 => array(
		'name' => '姨妈挑战者',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '在速度不良的服务器中磨练出了<br>钢铁般意志的玩家',
		'effect' => '初始攻防和行动CD都提高<br>推荐网速不佳的时候使用这张卡',
		'energy' => 0,
		'valid' => array(
			'att' => '250',
			'def' => '250',
			'skills' => array(
				'417' => '0', 
			),
		)
	),
	83 => array(
		'name' => '团战挑战者',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '每周都来到团战服打卡上班的玩家',
		'effect' => '开局装备带有属性防御的防具',
		'energy' => 100,
		'valid' => array(
			'ara' => '团队之星',
			'arak' => 'DA',
			'arae' => '15',
			'aras' => '3',
			'arask' => 'a',
		)
	),
	84 => array(
		'name' => '切糕挑战者',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '比起入场更喜欢下注切糕的玩家',
		'effect' => '杂兵掉落的切糕数量增加50%',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'418' => '0', 
			),
		)
	),
	85 => array(
		'name' => '海外挑战者',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '出没时间异于常人的玩家',
		'effect' => '服务器时间1:00~7:00期间造成的战斗伤害提高12%',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'419' => '0', 
			),
		)
	),
	86 => array(
		'name' => '暴食挑战者',
		'rare' => 'B',
		'pack' => 'Event Bonus',
		'desc' => '就算只少了10点体力<br>也要啃培根蛋的玩家',
		'effect' => '食用补给效果提高',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'421' => '0', 
			),
		)
	),
	87 => array(
		'name' => '未来战士',
		'rare' => 'A',
		'pack' => 'Event Bonus',
		'desc' => '中二动画里盛行的枪斗术<br>他掌握的很好',
		'effect' => '可以用枪托杀人',
		'energy' => 80,
		'valid' => array(
			'skills' => array(
				'422' => '0', 
			),
		)
	),
	88 => array(
		'name' => 'Dr.Clef',
		'rare' => 'S',
		'pack' => 'Event Bonus',
		'desc' => '撒旦本人',
		'effect' => '可以和SCP谈笑风生',
		'energy' => 160,
		'valid' => array(
			'skills' => array(
				'423' => '0', 
			),
		)
	),
	89 => array(
		'name' => '富一代',
		'rare' => 'S',
		'pack' => 'Event Bonus',
		'desc' => '富二代的爸爸',
		'effect' => '开局金钱为100并获得技能「理财」，且「理财」效果翻倍',
		'energy' => 100,
		'valid' => array(
			'money' => '100',
			'skills' => array(
				'67' => '0', 
			),
		)
	),
	90 => array(
		'name' => '攻坚',
		'rare' => 'S',
		'pack' => 'hidden',
		'desc' => 'mode16 card1',
		'effect' => '',
		'energy' => 0,
		'valid' => array(
			'club' => '94',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'58' => '0',
				'61' => '0', 
				'72' => '0', 
				'73' => '0', 
				'59' => '0', 
			),
			'skillpoint' => '5',
		)
	),
	91 => array(
		'name' => '副手',
		'rare' => 'S',
		'pack' => 'hidden',
		'desc' => 'mode16 card2',
		'effect' => '',
		'energy' => 0,
		'valid' => array(
			'club' => '95',
			'skills' => array(
				'10' => '0', 
				'39' => '0', 
				'12' => '0', 
				'255' => '0',
				'204' => '0', 
				'53' => '0', 
				'74' => '0', 
				'66' => '0', 
			),
		)
	),
	92 => array(
		'name' => '辅助',
		'rare' => 'S',
		'pack' => 'hidden',
		'desc' => 'mode16 card3',
		'effect' => '',
		'energy' => 0,
		'valid' => array(
			'club' => '96',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'69' => '0', 
				'23' => '0', 
				'233' => '0', 
				'235' => '0', 
				'410' => '0', 
				'232' => '0', 
			),
		)
	),
	93 => array(
		'name' => '软件测试工程师',
		'rare' => 'S',
		'pack' => 'hidden',
		'desc' => '“据称阁下乃软件测试界的精英，谨邀请阁下参加幻境除错任务，望阁下予以支持。”<br><span class="red" style="text-align:right">——红暮</span>',
		'effect' => '你其实对代码一窍不通，不过你搞野路子的经验很丰富。',
		'energy' => 0,
		'valid' => array(
			'club' => '7',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'424' => '0', 
				'425' => '0', 
				'426' => '0', 
				'427' => '0', 
				'429' => '0', 
				'428' => '0', 
				//'494' => '0', 
				//'495' => '0',
			),
			'itm6' => '任务指令书A',
			'itmk6' => 'Y',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	94 => array(
		'name' => '大自然的搬运工',
		'title' => '搬运工',
		'rare' => 'A',
		'pack' => 'Event Bonus',
		'desc' => '他能找到别人找不到的东西',
		'effect' => '每60秒可以随机获得一个地图上刷新的物品',
		'energy' => 250,
		'valid' => array(
			'skills' => array(
				'430' => '0', 
			),
		)
	),
	95 => array(
		'name' => '『冰炎（Rimefire）』',
		'title' => '『冰炎』',
		'rare' => 'S',
		'pack' => 'Crimson Swear',
		'desc' => '可能是家庭暴力的受害者',
		'effect' => '每次通常合成物品经验+3，全熟+3',
		'energy' => 90,
		'valid' => array(
			'skills' => array(
				'431' => '0', 
			),
		)
	),
	96 => array(
		'name' => '"Rimefire"',
		'rare' => 'S',
		'pack' => 'Event Bonus',
		'desc' => '可能是家庭暴力的受害者',
		'effect' => '每次通常合成物品经验+3，全熟+3',
		'energy' => 50,
		'valid' => array(
			'skills' => array(
				'431' => '0', 
			),
		)
	),
	97 => array(
		'name' => '蛋服挑战者',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '来自蛋服的反meta玩家',
		'effect' => '和玩家战斗时双方所有技能均无效',
		'energy' => 150,
		'valid' => array(
			'skills' => array(
				'433' => '0', 
			),
		)
	),
	98 => array(
		'name' => 'DK^BurNIng',
		'rare' => 'S',
		'pack' => 'Event Bonus',
		'desc' => '有时候输了比赛，总感觉队友不给力',
		'effect' => 'BOOM',
		'energy' => 140,
		'valid' => array(
			'skills' => array(
				'434' => '0', 
			),
		)
	),
	99 => array(
		'name' => '创世神2009',
		'rare' => 'S',
		'pack' => 'Best DOTO',
		'desc' => '09发明了大逃杀',
		'effect' => '称号固定为富家子弟，可以召唤5个佣兵，佣兵召唤和维持消耗大幅减少',
		'energy' => 100,
		'valid' => array(
			'club' => '11',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'69' => '0', 
				'68' => '0', 
				'55' => '0', 
				'67' => '0', 
				'435' => '0', 
				'66' => '0', 
			),
		)
	),
	100 => array(
		'name' => '吃人',
		'rare' => 'S',
		'pack' => 'Best DOTO',
		'desc' => '“胖头鱼开直播啦！”',
		'effect' => '使用逃跑指令时获得3点熟练和2点经验',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'437' => '0', 
			),
		)
	),
	101 => array(
		'name' => '那位先生',
		'rare' => 'S',
		'pack' => 'Best DOTO',
		'desc' => '大逃杀有8个称号，<br>其中有奶妈，很平衡',
		'effect' => '进场时间越晚，造成的伤害越高',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'438' => '0', 
			),
		)
	),
	102 => array(
		'name' => 'longdd',
		'rare' => 'S',
		'pack' => 'Best DOTO',
		'desc' => '给我龙神一个坐骑',
		'effect' => '大逃杀还是黄翔玩的好',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'439' => '0', 
			),
		)
	),
	103 => array(
		'name' => '末日使者',
		'rare' => 'A',
		'pack' => 'Best DOTO',
		'desc' => '爸爸',
		'effect' => '获得战斗技「父爱」',
		'energy' => 90,
		'valid' => array(
			'skills' => array(
				'440' => '0', 
			),
		)
	),
	104 => array(
		'name' => '钱四爷',
		'rare' => 'A',
		'pack' => 'Best DOTO',
		'desc' => '发烧=超频',
		'effect' => '根据自身异常状态数提高造成的物理伤害',
		'energy' => 70,
		'valid' => array(
			'skills' => array(
				'441' => '0', 
			),
		)
	),
	105 => array(
		'name' => 'EternaLEnVy',
		'rare' => 'A',
		'pack' => 'Best DOTO',
		'desc' => '洋装虽然穿在身，我心依然是中国心',
		'effect' => '我的祖先早已把我的一切，烙上中国印',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'442' => '0', 
			),
		)
	),
	106 => array(
		'name' => '军团指挥官',
		'rare' => 'A',
		'pack' => 'Best DOTO',
		'desc' => '谁跑谁是狗',
		'effect' => '每次击杀提升自身10点基础攻击力',
		'energy' => 80,
		'valid' => array(
			'skills' => array(
				'443' => '0', 
			),
		)
	),
	107 => array(
		'name' => 'ROTK',
		'rare' => 'C',
		'pack' => 'Best DOTO',
		'desc' => 'R!O!T!K!',
		'effect' => '杀谁，说话！',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'444' => '0', 
			),
		)
	),
	108 => array(
		'name' => '变体精灵',
		'rare' => 'B',
		'pack' => 'Best DOTO',
		'desc' => '它最喜欢的装备是BKB、<br>林肯和分身斧',
		'effect' => '基础攻击力视为0，基础防御力提高70%',
		'energy' => 60,
		'valid' => array(
			'skills' => array(
				'445' => '0', 
			),
		)
	),
	109 => array(
		'name' => '亚巴顿',
		'rare' => 'B',
		'pack' => 'Best DOTO',
		'desc' => '虽然还非常年轻，<br>但他已是魔霭族裔中知识水平最高者',
		'effect' => '获得可在一段时间内免疫战斗伤害的技能，只能使用一次',
		'energy' => 120,
		'valid' => array(
			'skills' => array(
				'446' => '0', 
			),
		)
	),
	110 => array(
		'name' => '远古冰魄',
		'rare' => 'B',
		'pack' => 'Best DOTO',
		'desc' => '即使身为代表熵本身的无上存在，<br>卡尔德还是逃不开包鸡包眼的命运',
		'effect' => '战斗伤害超过目标当前生命值90%以上时目标立即死亡',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'447' => '0', 
			),
		)
	),
	111 => array(
		'name' => '食人魔魔法师',
		'rare' => 'B',
		'pack' => 'Best DOTO',
		'desc' => '尽管很大程度上受到智商的制约，<br>食人魔魔法师仍能依靠纯熟的技巧在战斗中取胜',
		'effect' => '称号固定为最强大脑',
		'energy' => 100,
		'valid' => array(
			'club' => '21',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'239' => '0', 
				'240' => '0', 
				'241' => '0', 
				'242' => '0', 
				'243' => '0', 
				'244' => '0', 
			),
		)
	),
	112 => array(
		'name' => '冥界亚龙',
		'rare' => 'B',
		'pack' => 'Best DOTO',
		'desc' => '创世神2009的化身之一',
		'effect' => '击中你的敌人中毒；你造成的毒性伤害提高',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'448' => '0', 
			),
		)
	),
	113 => array(
		'name' => '莱恩',
		'rare' => 'C',
		'pack' => 'Best DOTO',
		'desc' => '天灾远程兵的噩梦',
		'effect' => '对杂兵造成的伤害提高8%',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'449' => '0', 
			),
		)
	),
	114 => array(
		'name' => '斧王',
		'rare' => 'C',
		'pack' => 'Best DOTO',
		'desc' => '砍了就跑！',
		'effect' => '对红血的敌人造成的伤害和命中率提高',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'450' => '0', 
			),
		)
	),
	115 => array(
		'name' => '祈求者',
		'rare' => 'C',
		'pack' => 'Best DOTO',
		'desc' => '他就是传说中的黑球卡',
		'effect' => '冷却时间-15%',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'451' => '0', 
			),
		)
	),
	116 => array(
		'name' => '帕克',
		'rare' => 'C',
		'pack' => 'Best DOTO',
		'desc' => '这不是撤退，是转进',
		'effect' => '战斗中被动受到大伤害时将传送到随机地图',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'452' => '0', 
			),
		)
	),
	117 => array(
		'name' => '星莲船挑战者',
		'rare' => 'S',
		'pack' => 'Way of Life',
		'desc' => 'AC大逃杀元老人物之一，<br>擅长上班摸鱼、下班挖坑',
		'effect' => '获得技能「挖坑」（误',
		'energy' => 150,
		'valid' => array(
			'skills' => array(
				'247' => '0', 
			),
		)
	),
	118 => array(
		'name' => '富零代',
		'rare' => 'M',
		'pack' => 'Event Bonus',
		'desc' => '富一代的一贫如洗的爸爸',
		'effect' => '开局金钱为0元',
		'energy' => 0,
		'valid' => array(
			'money' => 0,
		)
	),
	119 => array(
		'name' => '常磐之心',
		'rare' => 'A',
		'pack' => 'Event Bonus',
		'desc' => '<span class="yellow">我也是常磐森林出生的训练师！</span>',
		'effect' => '开局位于常磐森林。获得技能「通感」',
		'energy' => 100,
		'valid' => array(
			'pls' => 16,
			'skills' => array(
				'493' => '0', 
			)
		)
	),
	120 => array(
		'name' => '熊战士',
		'rare' => 'A',
		'pack' => 'Best DOTO',
		'desc' => '这张卡的设计者没玩过DOTA所以不知道怎么写介绍',
		'effect' => '连续攻击同一目标时（包含第一次），造成的最终伤害每次增加10%',
		'energy' => 120,
		'valid' => array(
			'skills' => array(
				'453' => '0', 
			),
		)
	),
	121 => array(
		'name' => '团购挑战者',
		'rare' => 'A',
		'pack' => 'Way of Life',
		'desc' => '热爱X宝团购与秒杀的挑战者',
		'effect' => '在商店一次购买超过1件物品时，价格降低25%；效果不与富家子弟基本特性叠加',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'454' => '0', 
			),
		)
	),
	122 => array(
		'name' => '陷阱挑战者',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '遇到名字一眼看不全的陷阱时<br>及时闭上双眼是保命窍门',
		'effect' => '你不会被名字超过9个字的陷阱杀死',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'457' => '0', 
			),
		)
	),
	123 => array(
		'name' => 'CTY',
		'rare' => 'B',
		'pack' => 'Best DOTO',
		'desc' => '前6分钟无敌的男人',
		'effect' => '游戏开始6分钟内，你免疫战斗和陷阱伤害（对少数NPC无效）',
		'energy' => 120,
		'valid' => array(
			'skills' => array(
				'455' => '0', 
			),
		)
	),
	124 => array(
		'name' => '6D挑战者',
		'rare' => 'A',
		'pack' => 'Way of Life',
		'desc' => '精通虫族6D一波rush战术的<br>星际1玩家',
		'effect' => '游戏开始6分钟内，你造成的伤害+40%',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'456' => '0', 
			),
		)
	),
	135 => array(
		'name' => '旋风管家',
		'rare' => 'A',
		'pack' => 'Standard Pack',
		'desc' => '熟练的宛如疾风玩家',
		'effect' => '称号固定为宛如疾风',
		'energy' => 100,
		'valid' => array(
			'club' => '6',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'255' => '0', 
				'248' => '0', 
				'251' => '0', 
				'253' => '0', 
				'252' => '0', 
				'254' => '0', 
			),
		)
	),
	136 => array(
		'name' => '董先森',
		'rare' => 'A',
		'pack' => 'Standard Pack',
		'desc' => '连任好不好啊',
		'effect' => '吼啊',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'245' => '0', 
			),
		)
	),
	137 => array(
		'name' => '大小姐',
		'rare' => 'A',
		'pack' => 'Way of Life',
		'desc' => '2016年，大逃杀战场被核子的火焰<br>笼罩！草木干枯，大地开裂，拳法家<br>像死绝了一样',
		'effect' => '但是拳法家并没有死绝！',
		'energy' => 130,
		'valid' => array(
			'club' => '19',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				
				'256' => '0', 
				'83' => '0',
				'258' => '0', 
				'257' => '0', 
				'260' => '0', 
				'259' => '0', 
				'274' => '0', 
				'262' => '0', 
				'263' => '0', 
				'261' => '0', 
			),
		)
	),
	138 => array(
		'name' => '藤甲兵',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '……穿在身上，渡江不沉，浸水不湿，刀箭皆不能入…… ——《三国演义》',
		'effect' => '受到的物理伤害-17%，但受到的火焰和灼焰伤害×2.5',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'463' => '0', 
			),
		)
	),
	139 => array(
		'name' => '抖M挑战者',
		'rare' => 'A',
		'pack' => 'Way of Life',
		'desc' => '即使每次都被黑熊暴风60连<br>也要PVE的玩家',
		'effect' => '每次受到攻击时，最高系熟练度+1',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'464' => '0', 
			),
		)
	),
	140 => array(
		'name' => 'FFF团资深团员',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => 'rt',
		'effect' => '开局携带火把和汽油，<br>造成的火焰和灼焰伤害×1.5',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'465' => '0', 
			),
			'itm3' => '火把',
			'itmk3' => 'WP',
			'itme3' => '40',
			'itms3' => '3',
			'itmsk3' => 'u',
			'itm4' => '汽油',
			'itmk4' => 'PS2',
			'itme4' => '200',
			'itms4' => '1',
			'itmsk4' => '',
		)
	),
	141 => array(
		'name' => '瘟疫法师',
		'rare' => 'A',
		'pack' => 'Best DOTO',
		'desc' => '已经装了A杖，斩死保证不能买活',
		'effect' => '攻击玩家时，额外附加相当于其(等级)%最大生命值的伤害，最多30%',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'466' => '0', 
			),
		)
	),
	142 => array(
		'name' => '裂魂人',
		'rare' => 'B',
		'pack' => 'Best DOTO',
		'desc' => '非洲进口质量保证',
		'effect' => '击中敌人时有17%几率使其进入眩晕状态，持续2.5秒',
		'energy' => 130,
		'valid' => array(
			'skills' => array(
				'467' => '0', 
			),
		)
	),
	143 => array(
		'name' => '母山岭巨人',
		'rare' => 'C',
		'pack' => 'Best DOTO',
		'desc' => '露娜引导一道聚集的月光能量<br>打击敌人，造成眩晕和伤害',
		'effect' => '被击中时有17%几率使敌人进入眩晕状态，持续2.5秒',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'468' => '0', 
			),
		)
	),
	144 => array(
		'name' => '炸弹人',
		'rare' => 'B',
		'pack' => 'Best DOTO',
		'desc' => '这个按钮是干什么的？',
		'effect' => '受到不低于90%最大生命值的战斗伤害且幸存时会自爆并造成巨大伤害',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'469' => '0', 
			),
		)
	),
	145 => array(
		'name' => '肉山大魔王',
		'rare' => 'S',
		'pack' => 'Best DOTO',
		'desc' => '虽然装备着不朽盾，却不会复活',
		'effect' => '获得技能「抗性」「天佑」「重击Lv1」',
		'energy' => 160,
		'valid' => array(
			'skills' => array(
				'462' => '0', 
				'251' => '0', 
				'400' => '1', 
			),
		)
	),
	146 => array(
		'name' => '矮人狙击手',
		'rare' => 'B',
		'pack' => 'Best DOTO',
		'desc' => '传：哥：',
		'effect' => '发动战斗技能时，造成的最终伤害+12%',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'470' => '0', 
			),
		)
	),
	147 => array(
		'name' => '气功大师',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '我这一拳过去，你可能会飞走',
		'effect' => '受到致命的战斗伤害时，每1点怒气可以抵消1.5点伤害',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'471' => '0', 
			),
		)
	),
	148 => array(
		'name' => '大魔导师',
		'rare' => 'A',
		'pack' => 'Best DOTO',
		'desc' => '读书人的事情，怎么能算偷呢',
		'effect' => '可以学习一个任意被动技能，且可以随意更换学习的技能',
		'energy' => 130,
		'valid' => array(
			'skills' => array(
				'472' => '0', 
			),
		)
	),
	149 => array(
		'name' => '殁境神蚀者',
		'rare' => 'B',
		'pack' => 'Best DOTO',
		'desc' => '三流过气偶像',
		'effect' => '你的攻击会消耗相当于9%当前体力值的体力并附加相同的伤害',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'473' => '0', 
			),
		)
	),
	150 => array(
		'name' => '减肥挑战者',
		'rare' => 'M',
		'pack' => 'Way of Life',
		'desc' => '今天锻炼了一天，头都是晕的',
		'effect' => '初始生命体力+100，食用补给效果减少30%',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'474' => '0', 
			),
			'hp' => '500',
			'mhp' => '500',
			'sp' => '500',
			'msp' => '500',
		)
	),
	151 => array(
		'name' => '虹光塑师',
		'rare' => 'B',
		'pack' => 'hidden',
		'desc' => 'mode3 card1',
		'effect' => '',
		'energy' => 0,
		'valid' => array(
			'club' => '20',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'23' => '0', 
				'420' => '0', 
				'476' => '0', 
			),
			'wep' => '精工的泰坦之击',
			'wepk' => 'WG',
			'wepe' => '70',
			'weps' => '233',
			'wepsk' => '',
			'itm3' => '精工的诸天之拳',
			'itmk3' => 'WP',
			'itme3' => '90',
			'itms3' => '140',
			'itmsk3' => '',
			'itm4' => '精工的熔火之心',
			'itmk4' => 'WD',
			'itme4' => '45',
			'itms4' => '∞',
			'itmsk4' => '',
			'itm5' => '精工的雄鹰之爪',
			'itmk5' => 'WC',
			'itme5' => '75',
			'itms5' => '∞',
			'itmsk5' => '',
			'itm6' => '精工的黑檀之寒',
			'itmk6' => 'WF',
			'itme6' => '36',
			'itms6' => '∞',
			'itmsk6' => '',
		)
	),
	152 => array(
		'name' => '唱戲挑戰者',
		'rare' => 'S',
		'pack' => 'Way of Life',
		'desc' => '你沒戲唱了，快去死吧！',
		'effect' => '开局赠送600点歌魂。对歌魂小于30的玩家和NPC造成的伤害+30%',
		'energy' => 100,
		'valid' => array(
			'ss' => 600,
			'skills' => array(
				'477' => '0', 
			),
		)
	),
	153 => array(
		'name' => '悲运挑战者',
		'rare' => 'S',
		'pack' => 'Way of Life',
		'desc' => '她说：“要有大逃杀。”<br>然后她就平地摔了',
		'effect' => '开局携带《ACFUN大逃杀原案》。',
		'energy' => 130,
		'valid' => array(
			'itm3' => '《ACFUN大逃杀原案》',
			'itmk3' => 'VVS',
			'itme3' => '100',
			'itms3' => '1',
			'itmsk3' => '478',
		)
	),
	154 => array(
		'name' => '掘豆挑战者',
		'rare' => 'A',
		'pack' => 'Way of Life',
		'desc' => '用掘豆带来笑容',
		'effect' => '以4000点LP开局。面包替换为掘豆盘。',
		'energy' => 100,
		'valid' => array(
			'hp' => 4000,
			'itm1' => '掘豆盘',
			'itmk1' => 'DA',
			'itme1' => '25',
			'itms1' => '10',
			'itmsk1' => '',
		)
	),
	155 => array(
		'name' => '数体教挑战者',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '四舍五入就是一个亿啊！',
		'effect' => '受到的战斗和陷阱伤害以百为单位进行四舍五入。',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'479' => '0', 
			),
		)
	),
	156 => array(
		'name' => '资本主义者',
		'rare' => 'S',
		'pack' => 'Event Bonus',
		'desc' => '如果有300%的利润，<br>它就敢犯任何罪行，甚至冒着被武神一枪抬走的危险。',
		'effect' => '获得技能「泡沫」，一次性让当前金钱翻倍，但之后每次探索或移动，金钱数减少50。',
		'energy' => 120,
		'valid' => array(
			'skills' => array(
				'480' => '0', 
			),
		)
	),
	157 => array(
		'name' => '洩矢诹访子',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '曾经统领着一个王国，<br>拥有着令人吃惊的信仰心',
		'effect' => '被你攻击的敌人会眩晕1秒',
		'energy' => 130,
		'valid' => array(
			'skills' => array(
				'481' => '0', 
			),
		)
	),
	158 => array(
		'name' => '博丽灵梦',
		'rare' => 'S',
		'pack' => 'Event Bonus',
		'desc' => '神社代代相传的【十万元cos】',
		'effect' => '称号固定为超能力者，且使用灵系武器时，有30%几率造成2倍物理伤害',
		'energy' => 150,
		'valid' => array(
			'club' => '9',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'18' => '0', 
				'65' => '0', 
				'73' => '0', 
				'76' => '0', 
				'74' => '0', 
				'482' => '0', 
			),
		)
	),
	159 => array(
		'name' => '氪金战士',
		'rare' => 'S',
		'pack' => 'Event Bonus',
		'desc' => '只要你充够了钱，你就能变得更强',
		'effect' => '可以花费500元使自己在接下来120秒内被玩家击杀后立即复活（次数不限）。可重复发动，但每次发动消耗翻倍。',
		'energy' => 150,
		'valid' => array(
			'skills' => array(
				'483' => '0', 
			),
		)
	),
	160 => array(
		'name' => '梦魇瑞尔提',
		'rare' => 'S',
		'pack' => 'Event Bonus',
		'desc' => '宝石噩梦才刚刚开始，亲爱的',
		'effect' => '称号固定为宝石骑士，且每隔一段时间能生成一个方块。开局包裹里道具全部变为随机方块。',
		'energy' => 150,
		'valid' => array(
			'club' => '20',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'23' => '0', 
				'420' => '0',
				'27' => '0', 
				'26' => '0',
				'24' => '0', 
				'54' => '0', 
				'25' => '0', 
				'272' => '0', 
			),
			'itm1' => array('红色方块', '绿色方块', '蓝色方块', '黄色方块', '金色方块', '银色方块', '黑色方块', '白色方块'),
			'itmk1' => 'X',
			'itme1' => '1',
			'itms1' => '1',
			'itmsk1' => '',
			'itm2' => array('红色方块', '绿色方块', '蓝色方块', '黄色方块', '金色方块', '银色方块', '黑色方块', '白色方块'),
			'itmk2' => 'X',
			'itme2' => '1',
			'itms2' => '1',
			'itmsk2' => '',
			'itm3' => array('红色方块', '绿色方块', '蓝色方块', '黄色方块', '金色方块', '银色方块', '黑色方块', '白色方块'),
			'itmk3' => 'X',
			'itme3' => '1',
			'itms3' => '1',
			'itmsk3' => '',
			'itm4' => array('红色方块', '绿色方块', '蓝色方块', '黄色方块', '金色方块', '银色方块', '黑色方块', '白色方块'),
			'itmk4' => 'X',
			'itme4' => '1',
			'itms4' => '1',
			'itmsk4' => '',
			'itm5' => array('红色方块', '绿色方块', '蓝色方块', '黄色方块', '金色方块', '银色方块', '黑色方块', '白色方块'),
			'itmk5' => 'X',
			'itme5' => '1',
			'itms5' => '1',
			'itmsk5' => '',
			'itm6' => array('红色方块', '绿色方块', '蓝色方块', '黄色方块', '金色方块', '银色方块', '黑色方块', '白色方块'),
			'itmk6' => 'X',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	161 => array(
		'name' => '红杀特工F',
		'rare' => 'B',
		'pack' => 'Crimson Swear',
		'desc' => '其实红杀里并没有超能力者。<br>不知道这个特工是哪儿来的。',
		'effect' => '开局灵熟+35',
		'energy' => 100,
		'valid' => array(
			'wf' => '35',
		)
	),
	162 => array(
		'name' => '解离挑战者',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '被禁死在雏菊之丘时还差最后30歌魂的玩家。',
		'effect' => '开局歌魂+30',
		'energy' => 100,
		'valid' => array(
			'ss' => '30',
			'mss' => '30',
		)
	),
	163 => array(
		'name' => '黄鸡挑战者',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '一切都是黄鸡的选择！',
		'effect' => '开局手里持有黄鸡方块',
		'energy' => 100,
		'valid' => array(
			'wep' => '黄鸡方块',
			'wepk' => 'WC',
			'wepe' => '1',
			'weps' => '∞',
			'wepsk' => 'z',
		)
	),
	164 => array(
		'name' => '发财挑战者',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '这个人有两大爱好，一是叫爸爸，二是假装自己是SCP',
		'effect' => '开局金钱是88，经验、怒气、全熟、歌魂和最大歌魂都是8',
		'energy' => 88,
		'valid' => array(
			'money' => '88',
			'exp' => '8',
			'rage' => '8',
			'wp' => '8',
			'wk' => '8',
			'wg' => '8',
			'wc' => '8',
			'wd' => '8',
			'wf' => '8',
			'ss' => '8',
			'mss' => '8',
		)
	),
	/////////////////////////////////////////////////
	
	
	//////////////////////////////////////////////////
	200 => array(
		'name' => '幻影斗将神 S.A.S',
		'title' => '幻影斗将神',
		'rare' => 'A',
		'pack' => 'Balefire Rekindle',
		'desc' => '“只要能为我的族人复仇，哪怕我<br>堕入永劫地狱也在所不惜！”',
		'effect' => '获得技能「嫉恶」',
		//：敌人每有1杀人数，其受到的最终伤害+21%。对玩家才有效
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'485' => '1', 
			),
		)
	),
	201 => array(
		'name' => '熵魔法传人 Howling',
		'title' => '熵魔法传人',
		'rare' => 'A',
		'pack' => 'Balefire Rekindle',
		'desc' => '“银月哨兵是不死之身！”',
		'effect' => '获得技能「无垠」',
		//：双方攻击结束时，如果你的HP<1，则有30%概率变为1。此技能每发动1次，这一概率减半。
		'energy' => 120,
		'valid' => array(
			'skills' => array(
				'486' => '1', 
			),
		)
	),
	202 => array(
		'name' => '通灵冒险家 星海',
		'title' => '通灵冒险家',
		'rare' => 'A',
		'pack' => 'Balefire Rekindle',
		'desc' => '“你住酒店时有没有第一时间确认<br>逃生通道的习惯？没有吧？我有。”',
		'effect' => '获得技能「后路」',
		//：你主动操作时因战斗或陷阱导致不足半血时，自动服用上一次吃的无毒回复道具直到HP回满或道具耗尽。这一技能不会产生道具CD。
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'487' => '0', 
			),
		)
	),
	203 => array(
		'name' => '银白愿天使 Annabelle',
		'title' => '银白愿天使',
		'rare' => 'A',
		'pack' => 'Balefire Rekindle',
		'desc' => '“只要你相信神的存在，<br>什么邪恶都没法左右你。”',
		'effect' => '获得技能「神眷」',
		//：战斗中受到的异常状态反弹给敌方。行动时不会因为异常状态受到HP伤害。
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'489' => '0', 
			),
		)
	),
	204 => array(
		'name' => '麻烦妖精 Sophia',
		'title' => '麻烦妖精',
		'rare' => 'A',
		'pack' => 'Balefire Rekindle',
		'desc' => '“今天的Sophia也是<br>元气满满的哟！”',
		'effect' => '获得技能「空想」',
		//：每60秒可以获得1个类别、效果、耐久和属性皆随机的「空想道具」，其效和耐合计值不超过发动时支付的体力。
		'energy' => 250,
		'valid' => array(
			'skills' => array(
				'490' => '0', 
			),
		)
	),
	205 => array(
		'name' => '飞行员 狂飙',
		'title' => '飞行员',
		'rare' => 'A',
		'pack' => 'Balefire Rekindle',
		'desc' => '“等我拿下了第一名，<br>我就要建立我自己的飞行俱乐部！”',
		'effect' => '称号固定为「宛如疾风」，且获得技能「神速」',
		'energy' => 120,
		'valid' => array(
			'club' => '6',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'255' => '0', 
				'248' => '0', 
				'251' => '0', 
				'253' => '0', 
				'252' => '0', 
				'254' => '0', 
				'41' => array('u' => '1'),
			),
		)
	),
	206 => array(
		'name' => '算法大师 薇娜',
		'title' => '算法大师',
		'rare' => 'A',
		'pack' => 'Balefire Rekindle',
		'desc' => '“借助量子迭代演算，<br>我们甚至可以计算出未来<br>——或者说，洞悉命运。”',
		'effect' => '称号固定为「最强大脑」，且在「规约」计算前伤害必然变为偶数',
		'energy' => 120,
		'valid' => array(
			'club' => '21',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'239' => '0', 
				'240' => '0', 
				'241' => '0', 
				'242' => '0', 
				'243' => '0', 
				'244' => '0', 
				'497' => '0', 
			),
		)
	),
	207 => array(
		'name' => '沙罗曼蛇的战乙女',
		'rare' => 'A',
		'pack' => 'Event Bonus',
		'desc' => '“只要是为了你，即使我被困在<br>永远的迷宫中，也没关系。”',
		'effect' => '获得技能「时停」，但不能赢得幸存胜利',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'500' => '0', 
				'501' => '0',
			),
		)
	),
	208 => array(
		'name' => '『无我（Absentia）』',
		'title' => '『无我』',
		'rare' => 'S',
		'pack' => 'Balefire Rekindle',
		'desc' => '狂飙最珍视的朋友和最厉害的对手，每次比赛总让狂飙屈居亚军。<br>然而她的时间永远定格在了3年前。<br>狂飙再也没有机会<br>从她那里夺回冠军宝座了。',
		'effect' => '有效APM越高，则先制率、回避率越高',
		'energy' => 120,
		'valid' => array(
			'skills' => array(
				'502' => '0', 
			),
		)
	),
	209 => array(
		'name' => '林氏软件董事长 林无月',
		'title' => '林氏软件董事长',
		'rare' => 'A',
		'pack' => 'Balefire Rekindle',
		'desc' => '<span class="evergreen">“虚拟幻境犹如一道曙光，<br>照亮了这个万物沉沦的黑暗年代。<br>我会守护这道希望之光，<br>直到生命的最后一刻。”</span>',
		'effect' => '你使用移动PC除必定成功之外，还能立刻增加禁区或者打乱未来禁区顺序；其他玩家入侵禁区失败概率上升',
		'energy' => 90,
		'valid' => array(
			'skills' => array(
				'503' => '0', 
			),
		)
	),
	210 => array(
		'name' => 'pop子',
		'title' => 'pop子',
		'rare' => 'S',
		'pack' => 'Way of Life',
		'desc' => '一月霸权动漫<br>《pop子与pipi美的日常》主角',
		'effect' => '「生气了吗……？」',
		'energy' => 0,
		'valid' => array(
			'mhp' => 100,
			'hp' => 100,
			'msp' => 100,
			'sp' => 100,
			'skills' => array(
				'504' => '0', 
			),
		)
	),
	
	1000 => array(
		'name'=>'补给品',
		'rare'=>'C',
		'desc'=>'教程模式用卡',
		'pack'=>'hidden',
		'effect'=>'教程模式技能载体',
		'energy'=>0,
		'valid' => array(
			'club' => '17',
			'itm3' => '紧急药剂',
			'itmk3' => 'Ca',
			'itme3' => '1',
			'itms3' => '10',
			'itmsk3' => '',
			'skills'=>array(
				'1000'=>'0'
			)
		)
	),
//	1001 => array(
//		'name'=>'小白鼠',
//		'rare'=>'C',
//		'desc'=>'荣耀模式用卡',
//		'pack'=>'hidden',
//		'effect'=>'荣耀模式技能载体',
//		'energy'=>0,
//		'valid' => array(
//			'itm4' => '紧急药剂',
//			'itmk4' => 'Ca',
//			'itme4' => '1',
//			'itms4' => '3',
//			'itmsk4' => '',
//			'itm5' => '生命探测器',
//			'itmk5' => 'ER',
//			'itme5' => '3',
//			'itms5' => '1',
//			'itmsk5' => '',
//			'skills'=>array(
//				'1001'=>'0'
//			)
//		)
//	),
);
}
?>
