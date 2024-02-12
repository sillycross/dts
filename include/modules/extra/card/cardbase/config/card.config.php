<?php
namespace cardbase{

//卡池运行时设定文件地址
$card_index_file = GAME_ROOT.'./gamedata/cache/card_index.config.php';

//禁止同名S、A、B卡入场的游戏模式
$card_force_different_gtype = Array(2,4);//18,19

//对卡片CD、类别CD有限制的游戏模式，基本上也是允许自选卡片的模式
$card_need_charge_gtype = Array(2,4);//18,19

//CD时间打折的模式，键名为gtype，键值为折数
$card_cooldown_discount_gtype = Array();//18=>0.5,19=>0.5

$cardtypecd=array(//卡片类别CD，单位秒
	'S' => 7200,//S卡类别CD：2小时
	'A' => 3600,//A卡类别CD：1小时
	'B' => 1800,//A卡类别CD：半小时
	'C' => 0,
	'M' => 0
);

$card_recrate_base=array(//单卡CD基础时间，单位秒
	'S' => 7200,
	'A' => 3600,
	'B' => 1800,
	'C' => 0,
	'M' => 0
);

$packlist=array(
	'Standard Pack',
	'Crimson Swear',
	'Top Players',
	'Way of Life',
	'Best DOTO',
	'Cyber Zealots',
	'東埔寨Protoject',//中文卡集名测试
	'Ranmen',
	'Balefire Rekindle',
	'Event Bonus',
	
	'Stealth',
	'hidden'
);

//卡包介绍
$packdesc = array(
	'Standard Pack' => '基本称号卡集。',
	'Crimson Swear' => '以游戏阵营「红杀」组织以及其马甲「金龙通讯社」为主题的卡集。',
	'Top Players' => '以本游戏发展史上那些著名玩家和重要协助者为纪念/捏他对象的卡集。',
	'Way of Life' => '大杂烩，主要以游戏方式以及同类游戏为捏他对象的卡集。',
	'Best DOTO' => '以电竞元素和电竞圈为吐槽对象的卡集。',
	'Balefire Rekindle' => '以游戏版本「复燃」的新增NPC角色和游戏设定为主题的卡集。',
	'Event Bonus' => '其他一些零散成就和活动奖励卡。',
	'Cyber Zealots' => '以赛博朋克和网络梗为捏他对象的卡集。',
	'東埔寨Protoject' => '以东之国旗舰级同人企划『朹方Project』为主题的卡集，与幻想作品如有雷同纯属必然。',
	'Ranmen' => '以随机性为特色的卡集。',
	'Stealth' => '一些需要显示卡片介绍的隐藏卡',
	'hidden' => '隐藏卡片，不会悬浮显示卡片介绍，如果你看到这句话请联系天然呆管理员',
);

//不参与抽卡的卡包
$pack_ignore_kuji = Array('Balefire Rekindle','Event Bonus');

//卡包实装的时间戳，可以用来隐藏卡包
$packstart = array(
	'Cyber Zealots' => 4476654671,
	//'東埔寨Protoject' => 4476654671,
	'Stealth' => 4476654671,
	'hidden' => 4476654671,
);

//卡包对应的图标，默认按Standard Pack的pack_default.png
$packicon = array(
	'Standard Pack' => 'pack_default.png',
	'Crimson Swear' => 'pack_crimson.png',
	'Top Players' => 'pack_topplayers.png',
	'Way of Life' => 'pack_wayoflife.png',
	'Best DOTO' => 'pack_game.png',
	'東埔寨Protoject' => 'pack_touhodia.png',
	'Ranmen' => 'pack_ranmen.png',
	'Balefire Rekindle' => 'annabelle_a.png',
	'Event Bonus' => 'pack_eventbonus.png',
);

$cardindex=array(//已停止更新，现在$cardindex是自动生成的，文件见下
	'S'=>array(1,  5,  16, 38, 39, 40, 41, 64, 65, 67, 71, 95, 99, 100,101,102,117,145,152,153,168,174),
	'A'=>array(2,  13, 14, 20, 22, 23, 26, 27, 32, 37, 43, 44, 45, 46, 47, 48, 49, 50, 68, 72, 75, 81, 103,104,105,106,120,121,124,135,136,137,139,141,148,154,169,173),
	'B'=>array(3,  12, 15, 21, 24, 25, 28, 35, 51, 52, 53, 54, 55, 56, 66, 69, 70, 76, 78, 80, 83, 97, 108,109,110,111,112,123,140,142,144,146,147,149,157,161,163,164,170,171,210,187,188),
	'C'=>array(4,  6,  7,  8,  9,  10, 11, 17, 18, 19, 29, 30, 31, 33, 34, 36, 57, 58, 59, 60, 61, 62, 73, 74, 77, 79, 82, 84, 85, 107,113,114,115,116,122,138,143,150,155,162,166,172,175,176,177,178,179,180,189),
	'M'=>array()
	//M卡的爆率实际属于C
	//pop子实际爆率是B
);

if(file_exists($card_index_file)) include $card_index_file;//载入真正的$cardindex

$card_rarecolor=array(
	'S'=>'gold b ',
	'A'=>'cyan b ',
	'B'=>'brickred b ',
	'C'=>'white b ',
	'M'=>'grey b '
);
$card_rarity_html = array(
	'S'=>'<span class="'.$card_rarecolor['S'].'">S</span>',
	'A'=>'<span class="'.$card_rarecolor['A'].'">A</span>',
	'B'=>'<span class="'.$card_rarecolor['B'].'">B</span>',
	'C'=>'<span class="'.$card_rarecolor['C'].'">C</span>',
	'M'=>'<span class="'.$card_rarecolor['M'].'">M</span>'
);	
$card_blink_rate_20 = Array(//碎卡比例（百分比），先判定是不是碎卡再判定是不是闪卡。对应$blink=20
	'S' => 1,
	'A' => 1,
	'B' => 1,
);
$card_blink_rate_10 = Array(//闪卡比例（百分比）。对应$blink=10。注意是累计概率，也就是这边减上面才是单项概率
	'S' => 6,
	'A' => 6,
	'B' => 6,
	'C' => 6,
);
//卡片返回切糕的价格
$card_price = array(
	'S'=>499,
	'A'=>99,
	'B'=>49,
	'C'=>30,
	'M'=>99
);
//碎卡和闪卡换算成切糕的倍率
$card_price_blink_rate =array(
	20=>25,
	10=>5,
);
$cards = array(
	0 => array(
		'name' => '挑战者',
		'rare' => 'C',
		'ignore_kuji' => true,
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
		'pack' => 'Way of Life',
		'desc' => '曾经镇守键刃墓场的BOSS',
		'effect' => '开局获得11份经验药',
		'energy' => 100,
		'valid' => array(
			'itm6' => '思念数据',
			'itmk6' => 'ME',
			'itme6' => '9',
			'itms6' => '11',
			'itmsk6' => '',
		)
	),
	2 => array(
		'name' => '初音大魔王',
		'rare' => 'A',
		'pack' => 'Top Players',
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
		'pack' => 'Top Players',
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
		'pack' => 'Way of Life',
		'desc' => '死后会召唤强力NPC的特殊小兵',
		'effect' => '开局全熟+5',
		'energy' => 0,
		'valid' => array(
			'wp' => '+5',
			'wk' => '+5',
			'wc' => '+5',
			'wg' => '+5',
			'wf' => '+5',
			'wd' => '+5',
		)
	),
	5 => array(
		'name' => '虚子',
		'rare' => 'S',
		'pack' => 'Way of Life',
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
				'35' => '0', 
				'36' => '0', 
				'37' => '0', 
				'38' => '0', 
				'34' => '0',
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
				'52' => '0', 
				'48' => '0', 
				'49' => '0', 
				'50' => '0', 
				'273' => '0',
				'51' => '0', 			
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
		'pack' => 'Best DOTO',
		'desc' => '一名头很硬的著名游戏玩家',
		'effect' => '获得技能「重击1」「硬化1」',
		'desc_skills' => '「重击1」：战斗时有20%概率提高物理伤害20%<br>「硬化1」：战斗中受到的物理伤害减少10%',
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
		'pack' => 'Way of Life',
		'desc' => '日本熊本县的吉祥物，<br>和大逃杀没有任何关系',
		'effect' => '获得技能「直死1」',
		'desc_skills' => '「直死1」：战斗时有微小概率直接杀死敌人',
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
		'pack' => 'Way of Life',
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
		'desc_skills' => '「神功」：战斗时获得的熟练度+1',
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
		'pack' => 'Top Players',
		'desc' => '经常死于不明AOE的顽强神触',
		'effect' => '获得技能「重击3」「硬化3」，但踩雷率提高',
		'desc_skills' => '「重击3」：战斗时有30%概率提高物理伤害50%<br>「硬化3」：战斗中受到的物理伤害减少30%',
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
		'pack' => 'Way of Life',
		'desc' => '身经百战见的多了……的沙包',
		'effect' => '开局获得3个技能点',
		'energy' => 0,
		'valid' => array(
			'skillpoint' => '+3',
		)
	),
	18 => array(
		'name' => 'G.D.S 业务员',
		'rare' => 'C',
		'pack' => 'Crimson Swear',
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
		'pack' => 'Best DOTO',
		'desc' => '为什么要放铁男？',
		'effect' => '获得技能「重击1」',
		'desc_skills' => '「重击1」：战斗时有20%概率提高物理伤害20%',
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
		'pack' => 'Way of Life',
		'desc' => 'SC出品的<br>一秒钟能合两把A刀的恐怖AI',
		'effect' => '获得技能「追击1」，但踩雷率提高',
		'desc_skills' => '「追击1」：战斗时有8%概率再次发动攻击',
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
		'energy' => 150,
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
		'rare' => 'B',
		'pack' => 'Standard Pack',
		'desc' => '熟练的高速成长玩家',
		'effect' => '称号固定为高速成长',
		'energy' => 100,
		'valid' => array(
			'club' => '10',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'78' => '0', 
				'225' => '0',
				'85' => '0', 				
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
				'40' => '0', 
				'45' => '0',
				'43' => '0', 
				'41' => '0', 
				'42' => '0',
				'44' => '0', 
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
				'104' => '0',
				'272' => '0',
				'105' => '0',
				'24' => '0',
				'54' => '0',
				'25' => '0',
				'106' => '0',
			),
		)
	),
	29 => array(
		'name' => '富二代',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '其实他也不是特别有钱',
		'effect' => '起始金钱为100元',
		'energy' => 0,
		'valid' => array(
			'money' => '+80',
		)
	),
	30 => array(
		'name' => '穆里尼奥',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '和全世界为敌的男人',
		'effect' => '起始怒气增加100点',
		'energy' => 0,
		'valid' => array(
			'rage' => '+100',
		)
	),
	31 => array(
		'name' => '变态',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '单纯的变态',
		'effect' => '？？？',
		'energy' => 0,
		'valid' => array(
			'arb' => '女生校服',
			'arbk' => 'DB',
			'arbe' => '5',
			'arbs' => '15',
			'arbsk' => '',
			'arh' => '女生内裤',
			'arhk' => 'DB',
			'arhe' => '5',
			'arhs' => '10',
			'arhsk' => '',
			'arf' => '女生丝袜',
			'arfk' => 'DF',
			'arfe' => '5',
			'arfs' => '10',
			'arfsk' => '',
			'gd' => 'm',
			'icon' => '9',
		)
	),
	32 => array(
		'name' => 'BurNIng',
		'rare' => 'A',
		'pack' => 'Best DOTO',
		'desc' => '这个人用鸟习惯特别不好',
		'effect' => '自带雷达',
		'energy' => 80,
		'valid' => array(
			'itm6' => '雷达',
			'itmk6' => 'ER',
			'itme6' => '44',
			'itms6' => '44',
			'itmsk6' => '2',
		)
	),
	33 => array(
		'name' => '拳法家',
		'rare' => 'M',
		'pack' => 'Standard Pack',
		'desc' => '孤高的拳法家，<br>对各种小伎俩不屑一顾',
		'effect' => '拳法家不需要多余的技能',
		'desc_skills' => '开局HP、最大HP、殴熟和攻击力都增加50，但失去大部分技能',
		'energy' => 0,
		'valid' => array(
			'club' => '14',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
			),
			'hp' => '+50',
			'mhp' => '+50',
			'wp' => '+50',
			'att' => '+50',
		)
	),
	34 => array(
		'name' => '草药学家',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '药学家是什么人，<br>为什么要伤害他们？',
		'effect' => '获得黑衣组织技能「毒师」',
		'desc_skills' => '「毒师」：可以查毒，且给食物下毒造成伤害翻倍',
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
		'pack' => 'Best DOTO',
		'desc' => '著名的生物学家，兼任创世神',
		'effect' => '可以召唤保安',
		'desc_skills' => '获得富家子弟技能「佣兵」<br>「佣兵」：可以花钱召唤佣兵，游戏中最多使用4次',
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
		'desc' => '为什么他只有一只手呢',
		'effect' => '获得富家子弟技能「网购」',
		'desc_skills' => '「网购」：可以在任意地点访问商店',
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
		'pack' => 'Way of Life',
		'desc' => '美国著名新闻工作者',
		'effect' => '初始属性不知道高到哪里去了',
		'desc_skills' => '开局HP、最大HP、SP、最大SP、攻击力、防御力、金钱和全系熟练度都增加50点',
		'energy' => 150,
		'valid' => array(
			'hp' => '+50',
			'mhp' => '+50',
			'sp' => '+50',
			'msp' => '+50',
			'att' => '+50',
			'def' => '+50',
			'money' => '+50',
			'wp' => '+50',
			'wk' => '+50',
			'wc' => '+50',
			'wg' => '+50',
			'wd' => '+50',
			'wf' => '+50',
		)
	),
	38 => array(
		'name' => '『芙蓉』',
		'ruby' => 'Fleur',
		'rare' => 'S',
		'pack' => 'Crimson Swear',
		'desc' => '低调行事的年轻女性，<br>红暮的青梅竹马。<br>目前是红暮的影一样的存在。<br>担当红杀组织中并不存在的<br>隐秘行动课程的教头。',
		'effect' => '获得技能「疾走」：被发现率降低15%，主动探索先攻率增加10%，爆系和斩系伤害提高20%',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'405' => '0', 
			),
		)
	),
	39 => array(
		'name' => '『红暮』',
		'ruby' => 'Crimson',
		'rare' => 'S',
		'pack' => 'Crimson Swear',
		'desc' => '英姿飒爽的年轻女性。<br>表面上是城内世家的千金，以及湾城最大的实业『金龙通讯社』的CEO，<br>实际是佣兵组织『红杀』的现任当家',
		'effect' => '揍起人来当然也是一把好手',
		'desc_skills' => '获得技能「烈击」：战斗时有60%概率提高物理伤害75%',
		'energy' => 180,
		'valid' => array(
			'skills' => array(
				'400' => '5', 
			),
		)
	),
	40 => array(
		'name' => '『蓝凝』',
		'ruby' => 'Azure',
		'rare' => 'S',
		'pack' => 'Crimson Swear',
		'desc' => '<span class="ltazure b">“蓝凝我觉得啊，<br>这个地方没什么好写的。<br>总之我比红暮可强得多了，<br>哈哈哈！”</span>',
		'effect' => '<span class="ltazure b">“蓝凝觉得你进游戏实际体验一下<br>比较好哦！”</span>',
		'desc_skills' => '<span class=\'ltazure b\'>“快进游戏实际体验一下吧！”</span>',
		'energy' => 60,
		'valid' => array(
			'hp' => '-140',
			'mhp' => '-140',
			'club' => '17',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'406' => '0',
				'432' => '0', 
				'462' => '0', 
			),
		)
	),
	41 => array(
		'name' => '『丁香』',
		'ruby' => 'Lila',
		'rare' => 'S',
		'pack' => 'Crimson Swear',
		'desc' => '芙蓉的妹妹，现年初二，<br>在一般的平民初中就读。是学校演剧部的部长，也备有无数的戏服用品。<br>爱好是写剧本和读其他人的剧本',
		'effect' => '获得技能「红石」：生命值高于一半则把攻击力的一半加到防御力上，反之则把防御力的一半加到攻击力上',
		'desc_skills' => '增加值不会超过10万',
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
		'effect' => '获得技能「疾走」：被发现率降低15%，主动探索先攻率增加10%，爆系和斩系伤害提高20%',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'405' => '0', 
			),
		)
	),
	43 => array(
		'name' => '『飞龙』',
		'ruby' => 'Wyvern',
		'rare' => 'A',
		'pack' => 'Crimson Swear',
		'desc' => '红暮和蓝凝的爷爷。<br>前代红杀将军。<br>在二人的父亲『幻铁』行踪不明后，抚养二人长大。目前隐居在城外的乡村中卖中药为生。',
		'effect' => '获得技能「不动」：<br>受到的战斗和陷阱伤害-10%',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'409' => '0', 
			),
		)
	),
	44 => array(
		'name' => '『翼虎』',
		'ruby' => 'Manticore',
		'rare' => 'A',
		'pack' => 'Crimson Swear',
		'desc' => '『飞龙』的好友。<br>前代红杀菁英。<br>据说只要他的盾还在身上，<br>没什么东西能伤得了他。<br>目前他的盾由红暮收藏，他自己则在飞龙之前就已经退役了。',
		'effect' => '获得技能「猛风」：<br>探索体力消耗-12，战斗怒气获得+4',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'410' => '0', 
			),
		)
	),
	45 => array(
		'name' => '『铁城』',
		'ruby' => 'Rook',
		'rare' => 'A',
		'pack' => 'Crimson Swear',
		'desc' => '红杀的拳脚教头',
		'effect' => '殴系伤害+20%，拳头伤害+40%',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'408' => '1', 
			),
		)
	),
	46 => array(
		'name' => '『灵翼』',
		'ruby' => 'Bishop',
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
		'name' => '『破石』',
		'ruby' => 'Knight',
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
		'name' => '『银锤』',
		'ruby' => 'Pawn',
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
		'name' => '『电返』',
		'ruby' => 'King',
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
		'name' => '『三步』',
		'ruby' => 'Queen',
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
		'effect' => '开局殴熟+50',
		'energy' => 100,
		'valid' => array(
			'wp' => '+50',
		)
	),
	52 => array(
		'name' => '红杀特工K',
		'rare' => 'B',
		'pack' => 'Crimson Swear',
		'desc' => '善于使用冷兵器的红杀特工',
		'effect' => '开局斩熟+50',
		'energy' => 100,
		'valid' => array(
			'wk' => '+50',
		)
	),
	53 => array(
		'name' => '红杀特工C',
		'rare' => 'B',
		'pack' => 'Crimson Swear',
		'desc' => '善于使用飞行道具的红杀特工',
		'effect' => '开局投熟+50',
		'energy' => 100,
		'valid' => array(
			'wc' => '+50',
		)
	),
	54 => array(
		'name' => '红杀特工G',
		'rare' => 'B',
		'pack' => 'Crimson Swear',
		'desc' => '善于使用枪械的红杀特工',
		'effect' => '开局射熟+50',
		'energy' => 100,
		'valid' => array(
			'wg' => '+50',
		)
	),
	55 => array(
		'name' => '红杀特工D',
		'rare' => 'B',
		'pack' => 'Crimson Swear',
		'desc' => '善于使用爆炸物的红杀特工',
		'effect' => '开局爆熟+50',
		'energy' => 100,
		'valid' => array(
			'wd' => '+50',
		)
	),
	56 => array(
		'name' => '红杀特工A',
		'rare' => 'B',
		'pack' => 'Crimson Swear',
		'desc' => '战斗风格多变的红杀特工',
		'effect' => '开局灵熟以外的熟练度+40',
		'energy' => 100,
		'valid' => array(
			'wp' => '+40',
			'wk' => '+40',
			'wc' => '+40',
			'wg' => '+40',
			'wd' => '+40',
		)
	),
	57 => array(
		'name' => '书卷使卡玛',
		'title' => '书卷使',
		'rare' => 'C',
		'pack' => 'Crimson Swear',
		'desc' => '<span class="white b">『时空特使里默默无闻的工作人员。<br>某次事件之后就消失了。』</span>',
		'effect' => '开局攻防增加23点',
		'energy' => 0,
		'valid' => array(
			'att' => '+23',
			'def' => '+23',
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
			'att' => '-80',
			'def' => '+80',
		)
	),
	61 => array(
		'name' => 'G.D.S 领导',
		'rare' => 'M',
		'pack' => 'Crimson Swear',
		'desc' => '由于长期坐办公室，<br>他的身材已经严重走形了',
		'effect' => '初始经验值增加65点，但体力大幅下降',
		'energy' => 0,
		'valid' => array(
			'sp' => '-360',
			'msp' => '-360',
			'exp' => '+65',
		)
	),
	62 => array(
		'name' => 'G.D.S 扫地大妈',
		'rare' => 'C',
		'pack' => 'Crimson Swear',
		'desc' => '金龙通讯社的第一道坚强防线，在公司的日常运转中也发挥着极大的作用',
		'effect' => '获得富家子弟技能「人杰」',
		'desc_skills' => '「人杰」：战斗中的熟练度以六系中最高的熟练度计算',
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
		'effect' => '称号固定为边缘行者，技能「过载」大幅强化，且开局即解锁',
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
		'effect' => '称号固定为边缘行者，技能「过载」强化，且开局即解锁',
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
		'pack' => 'Top Players',
		'desc' => 'ACFUN大逃杀画师，游戏中的萌妹子头像和UI都出自她手',
		'effect' => '获得技能「影像」：<br>减半偶数战斗伤害，直至其为奇数',
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
		'pack' => 'Top Players',
		'desc' => 'ACFUN大逃杀史上第一神触',
		'effect' => '获得技能「手熟」：<br>战斗中获得的熟练度+1',
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
			'itm6' => '【风神的神德】',
			'itmk6' => 'WF',
			'itme6' => '233',
			'itms6' => '10',
			'itmsk6' => 'z',
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
			'itm6' => '驱云弹',
			'itmk6' => 'EW',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '1',
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
		'pack' => 'Top Players',
		'desc' => '著名的小黄系列玩家，设计了《小黄的大师球》和初版游戏王的合成',
		'effect' => '获得技能「鹰眼」：<br>600秒内必中（爆系和重枪无效），发动时每有1点投熟额外延长4秒，每局只能使用1次。',
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
		'pack' => 'Ranmen',
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
		'pack' => 'Top Players',
		'desc' => '唉',
		'effect' => '你知道的',
		'desc_skills' => '开局装备灯笼裤',
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
			'hp' => '+300',
			'sp' => '+300',
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
		'desc_skills' => '实际上携带的是「杏仁豆腐的ID卡模样的杏仁豆腐」',
		'energy' => 100,
		'valid' => array(
			'itm6' => '杏仁豆腐的ID卡模样的杏仁豆腐',
			'itmk6' => 'HB',
			'itme6' => '77',
			'itms6' => '77',
			'itmsk6' => 'Z',
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
			'hp' => '+400',
			'mhp' => '+400',
			'club' => '17',
			'skills' => array(
				'12' => '0', 
			)
		)
	),
	80 => array(
		'name' => '赛博精神病',
		'rare' => 'B',
		'pack' => 'Standard Pack',
		'desc' => '熟练的边缘行者玩家',
		'effect' => '称号固定为边缘行者',
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
		'pack' => 'Ranmen',
		'desc' => '来自早已消亡的篝火服的神秘玩家',
		'effect' => '随机发动一张身份卡的效果<br>S:20% A:40% B:20% C:20%',
		'energy' => 100,
		'valid' => array(
			'cardchange' => Array(
				'S_odds' => 20,
				'A_odds' => 40,
				'B_odds' => 20,
				'C_odds' => 20,
				'allow_EB' => true,//开启后会把Event Bonus等需要特殊方式才能获得的卡也一并考虑
				'ignore_cards' => Array(237, 241, 344, 381)//机制上必定选不到自己，这里可以放其他不想被选到的卡
			)
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
			'att' => '+150',
			'def' => '+150',
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
		'effect' => '获得技能「修仙」：<br>服务器时间1:00~7:00期间造成的战斗伤害提高12%',
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
		'effect' => '食用补给效果提高20%',
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
		'desc_skills' => '用枪械当做钝器攻击时伤害不减，且可以正常造成属性伤害',
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
		'desc_skills' => '免疫SCP对你造成的伤害。<br>注意：场上有你之外的其他玩家时，你与SCP交战所获得的经验大幅下降。',
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
		'desc_skills' => '翻倍后，每升一级将获得300元（称号为富家则为400元）',
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
		'desc' => 'PVE房间（gtype=16）1号位。正常是看不到这句话的。',
		'effect' => '',
		'hidden_cardframe' => 1,//隐藏卡面显示
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
			'skillpoint' => '+5',
		)
	),
	91 => array(
		'name' => '副手',
		'rare' => 'S',
		'pack' => 'hidden',
		'desc' => 'PVE房间（gtype=16）2号位。正常是看不到这句话的。',
		'effect' => '',
		'hidden_cardframe' => 1,//隐藏卡面显示
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
		'desc' => 'PVE房间（gtype=16）3号位。你刚才偷偷看了对吧？',
		'effect' => '',
		'hidden_cardframe' => 1,//隐藏卡面显示
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
		'desc' => '“据称阁下乃软件测试界的精英，谨邀请阁下参加幻境除错任务，望阁下予以支持。”<br><span class="red b" style="text-align:right">——红暮</span>',
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
		'energy' => 150,
		'valid' => array(
			'skills' => array(
				'430' => '0', 
			),
		)
	),
	95 => array(
		'name' => '『冰炎』',
		'ruby' => 'Rimefire',
		'rare' => 'S',
		'pack' => 'Crimson Swear',
		'desc' => '可能是家庭暴力的受害者',
		'effect' => '每次通常合成物品经验+6，全熟+4',
		'energy' => 100,
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
		'effect' => '每次通常合成物品经验+6，全熟+4',
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
		'desc_skills' => '获得技能「核弹」：爆炸伤害+70%',
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
		'desc_skills' => '获得技能「面子」<br>「面子」：被同种武器第二次攻击时最终伤害-75%，但不能被连续触发',
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
		'desc_skills' => '「父爱」：本次战斗中对方技能无效，且【烧伤】、【中毒】并在40秒内无法解除',
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
		'effect' => '获得技能「嗑药」：每有一处异常状态，自己造成的物理伤害就提高35%',
		//'desc_skills' => '获得技能「嗑药」：每有一处异常状态，自己造成的物理伤害就提高35%',
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
		'desc_skills' => '获得技能「E术」：武器伤害浮动范围大幅增加',
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
		'desc_skills' => '获得技能「怒吼」：造成的战斗伤害×2，命中率大幅降低',
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
		'effect' => '基础攻击力视为减半，基础防御力视为1.7倍',
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
		'effect' => '获得技能「死线」：发动后30秒内免疫一切战斗伤害，每局只能使用1次',
		'energy' => 180,
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
		'pack' => 'Standard Pack',
		'desc' => '尽管很大程度上受到智商的制约，<br>食人魔魔法师仍能依靠纯熟的技巧在战斗中取胜',
		'effect' => '称号固定为最强大脑',
		'energy' => 100,
		'valid' => array(
			'club' => '21',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'80' => '0', 
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
		'rare' => 'A',
		'pack' => 'Way of Life',
		'desc' => 'AC大逃杀元老人物之一，<br>擅长上班摸鱼、下班挖坑',
		'effect' => '获得技能「挖坑」，但不能选到肌肉兄贵称号',
		'desc_skills' => '「挖坑」：在你当前地点放置两个毒性陷阱，效果值为基础攻击力x距上次挖坑的分钟数(最多为3)<br>此外你不会遭遇自己放置的陷阱',
		'energy' => 180,
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
			'money' => '-20',
		)
	),
	119 => array(
		'name' => '常磐之心',
		'rare' => 'A',
		'pack' => 'Event Bonus',
		'desc' => '<span class="yellow b">我也是常磐森林出生的训练师！</span>',
		'effect' => '开局位于常磐森林。获得技能「通感」',
		'desc_skills' => '「通感」：可以自由选择把战斗中获得的熟练度、经验值和怒气全部转化为其中某一项',
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
	//这里曾经有10张卡的空白，后来被我填满了。所以这部分卡跟之前和之后的卡是有代沟的
	125 => array(
		'name' => '植物人',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '第一次见到肥料就一口啃下去的萌新',
		'effect' => '开局携带肥料',
		'energy' => 0,
		'valid' => array(
			'itm6' => '肥料',
			'itmk6' => 'HB',
			'itme6' => '200',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	126 => array(
		'name' => '闹球肾',
		'rare' => 'C',
		'pack' => 'Top Players',
		'desc' => '一个半角引号引发的血案',
		'effect' => '开局携带\' OR 1',
		'energy' => 0,
		'valid' => array(
			'itm6' => '’ OR 1',
			'itmk6' => 'WF',
			'itme6' => '190',
			'itms6' => '5',
			'itmsk6' => '',
		)
	),
	127 => array(
		'name' => '坂田铜时',
		'rare' => 'C',
		'pack' => 'Top Players',
		'desc' => '对幻境使用受王拳吧',
		'effect' => '使用效果值大于10000的殴系武器时，不会损耗耐久值',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'532' => '0', 
			),
		)
	),
	128 => array(
		'name' => '网魔',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '谁把天才变成了魔兽？',
		'effect' => '获得边缘行者技能「网瘾」',
		'desc_skills' => '「网瘾」：使用移动PC解除禁区成功率为95%，且完全无风险',
		'energy' => 0,
		'valid' => array(
		  'skills' => array(
				'233' => '0', 
			),
		)
	),
	129 => array(
		'name' => '吉祥物',
		'rare' => 'M',
		'pack' => 'Standard Pack',
		'desc' => '总之就是非常可爱',
		'effect' => '称号固定为走路萌物',
		//'desc_skills' => '「网瘾」：使用移动PC解除禁区成功率为95%，且完全无风险',
		'energy' => 0,
		'valid' => array(
		  'club' => '17',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0',
			),
		)
	),
	130 => array(
		'name' => '士兵 丁',
		'rare' => 'M',
		'pack' => 'Top Players',
		'desc' => '远古时期参与出征265g，为dts菜鸟们长脸的神秘黑客高手',
		'effect' => '称号固定为装逼战士。开局携带能增长全系熟练度的药剂',
		//'desc_skills' => '「网瘾」：使用移动PC解除禁区成功率为95%，且完全无风险',
		'energy' => 0,
		'valid' => array(
		  'club' => '97',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0',
			),
			'itm6' => 'DTS.EXE',
			'itmk6' => 'MV',
			'itme6' => '1',
			'itms6' => '100',
			'itmsk6' => '',
		)
	),
	131 => array(
		'name' => '北京推倒你',
		'rare' => 'B',
		'pack' => 'Top Players',
		'desc' => '虽然代码令人一言难尽，但他创造了宝石强化系统',
		'effect' => '开局携带『祝福宝石』',
		//'desc_skills' => '「网瘾」：使用移动PC解除禁区成功率为95%，且完全无风险',
		'energy' => 100,
		'valid' => array(
			'itm6' => '『祝福宝石』',
			'itmk6' => 'Y',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	132 => array(
		'name' => '搬运⑨课',
		'rare' => 'C',
		'pack' => 'Top Players',
		'desc' => '弹幕网站上古时期的一群知名UP主，<br>也是这个游戏最早的一批玩家',
		'effect' => '看看弹幕的力量！',
		'desc_skills' => '开局携带10颗重装弹药',
		'energy' => 0,
		'valid' => array(
			'itm6' => '重装子弹',
			'itmk6' => 'GBh',
			'itme6' => '1',
			'itms6' => '10',
			'itmsk6' => '',
		)
	),
	133 => array(
		'name' => '777',
		'rare' => 'A',
		'pack' => 'Top Players',
		'desc' => '桃箱服的提供者，为常磐系大逃杀的延续赌上了自己的备案',
		'effect' => '开局携带「背岸之盾」',
		'desc_skills' => '「背岸之盾」：拥有777防御的随机单防手部防具',
		'energy' => 100,
		'valid' => array(
			'ara' => '背岸之盾',
			'arak' => 'DA',
			'arae' => '777',
			'aras' => '7',
			'arask' => Array('P','K','G','C','D','F','U','I','E','q','W','R'),
		)
	),
	134 => array(
		'name' => '我是高达',
		'rare' => 'C',
		'pack' => 'Top Players',
		'desc' => '这是什么？是尸体，凸一下',
		'effect' => '尸体为什么会有无敌时间呢？',
		'desc_skills' => '开局携带凸眼鱼',
		'energy' => 0,
		'valid' => array(
			'itm6' => '凸眼鱼',
			'itmk6' => Array('HB','HS','HH','PB','PH','PS'),
			'itme6' => '120',
			'itms6' => '5',
			'itmsk6' => '',
		)
	),
	
	//10张卡的空白结束了
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
		'pack' => 'Way of Life',
		'desc' => '连任好不好啊',
		'effect' => '吼啊',
		'desc_skills' => '获得技能「钦定」：天气对你的所有负面影响均改为同等数值的正面加成',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'245' => '0', 
			),
		)
	),
	137 => array(
		'name' => '大小姐',
		'rare' => 'B',
		'pack' => 'Standard Pack',
		'desc' => '2016年，<br>大逃杀战场被核子的火焰笼罩！<br>草木干枯，大地开裂，<br>拳法家像死绝了一样',
		'effect' => '但是拳法家并没有死绝！',
		'desc_skills' => '称号固定为铁拳无敌',
		'energy' => 150,
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
			'itm5' => '火把',
			'itmk5' => 'WP',
			'itme5' => '40',
			'itms5' => '3',
			'itmsk5' => 'u',
			'itm6' => '汽油',
			'itmk6' => 'PS2',
			'itme6' => '200',
			'itms6' => '1',
			'itmsk6' => '',
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
		'effect' => '获得技能「抗性」「天佑」「重击1」',
		'desc_skills' => '「抗性」：若敌人对你发动了战斗技，你受到的最终伤害-35%
		<br>「天佑」：受到大伤害后5秒内免疫一切战斗或陷阱伤害（对部分NPC无效）
		<br>「重击1」：战斗时有20%概率提高物理伤害20%',
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
		'desc' => '目光清澈，极度自信，且智商逐年<br>升高，最后完全变成天才',
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
			'hp' => '+100',
			'mhp' => '+100',
			'sp' => '+100',
			'msp' => '+100',
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
			'ss' => '+600',
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
		'effect' => '开局携带《ACFUN大逃杀原案》',
		'desc_skills' => '《ACFUN大逃杀原案》：使用后获得150点全系熟练度，但是之后你会更加倒霉',
		'energy' => 120,
		'valid' => array(
			'itm6' => '《ACFUN大逃杀原案》',
			'itmk6' => 'VVS',
			'itme6' => '150',
			'itms6' => '1',
			'itmsk6' => '478',
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
			'hp' => '+3600',
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
		'effect' => '获得技能「泡沫」：一次性让当前金钱翻倍，但之后每次探索或移动，金钱数都减少50。',
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
		'pack' => '東埔寨Protoject',
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
		'energy' => 100,
		'valid' => array(
			'club' => '20',
			'skills' => array(
				'10' => '0',
				'11' => '0',
				'12' => '0',
				'23' => '0',
				'104' => '0',
				'272' => '0',
				'105' => '0',
				'24' => '0',
				'54' => '0',
				'25' => '0',
				'106' => '0',
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
		'effect' => '开局灵熟+70',
		'energy' => 100,
		'valid' => array(
			'wf' => '+70',
		)
	),
	162 => array(
		'name' => '解离挑战者',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '被禁死在雏菊之丘时还差最后30歌魂的玩家。',
		'effect' => '开局歌魂+30',
		'energy' => 0,
		'valid' => array(
			'ss' => '+30',
			'mss' => '+30',
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
		'effect' => '开局金钱是88，经验、怒气、全熟、歌魂和最大歌魂都增加8',
		'energy' => 88,
		'valid' => array(
			'money' => '+68',
			'exp' => '+8',
			'rage' => '+8',
			'wp' => '+8',
			'wk' => '+8',
			'wg' => '+8',
			'wc' => '+8',
			'wd' => '+8',
			'wf' => '+8',
			'ss' => '+8',
			'mss' => '+8',
		)
	),
	165 => array(
		'name' => '世界制御猫',
		'rare' => 'B',
		'pack' => 'Event Bonus',
		'desc' => '猫用四条腿走路！',
		'effect' => '所以不是猫！',
		'desc_skills' => '找到并装备「巨大灯泡」以后，你将免疫一切战斗和陷阱伤害。<br>但是如果你装备、包裹及视野中都不存在【巨大灯泡】，你会立即从这局游戏中消失',
		'energy' => 120,
		'valid' => array(
			'skills' => array(
				'505' => '0', 
			),
		)
	),
	166 => array(
		'name' => '云玩家',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '快超量星尘龙啊，你艾斯比吗？',
		'effect' => '进行状况中，你的伤害值会夸大几百倍',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'511' => '0', 
			),
		)
	),
	167 => array(
		'name' => '地下挑战者 Carnage',
		'title' => '地下挑战者',
		'rare' => 'S',
		'pack' => 'Event Bonus',
		'desc' => '说清场就清场的恶魔',
		'effect' => '场上每有1名角色死亡，你的最终伤害就增加1%',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'515' => '0', 
			),
		)
	),
	168 => array(
		'name' => '飞雪大大',
		'rare' => 'S',
		'pack' => 'Top Players',
		'desc' => '飞雪大魔王<br><br>—————魔王分割线—————<br><br>沙包们最好的朋友',
		'effect' => '如果你受到的伤害比上一次伤害少，则不会因为这次伤害失去HP。',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'516' => '0', 
			),
		)
	),
	169 => array(
		'name' => '冰冻青蛙',
		'rare' => 'A',
		'pack' => 'Top Players',
		'desc' => '曾经的ACFUN大逃杀开发者，<br>其脑洞对玩家十分<ruby>友<rt>bao</rt></ruby><ruby>好<rt>she</rt></ruby>',
		'effect' => '咸鱼的目标从来不是杀死玩家',
		'desc_skills' => '开局装备有「碎甲」属性的饰品',
		//'desc_skills' => '获得技能「鱼弹」：战斗技，本次物理伤害变成0，但对方随机一件防具的耐久值下降你的武器效果值，每局只能发动2次',
		'energy' => 100,
		'valid' => array(
			'art' => Array('油炖萌物「金鲤」', '油炖萌物「石斑」'),
			'artk' => 'A',
			'arte' => '12',
			'arts' => '450',
			'artsk' => 'z^ac1',
		)
	),
	170 => array(
		'name' => '镜湖楼主',
		'rare' => 'B',
		'pack' => 'Top Players',
		'desc' => '电波服曾经的服务器提供者，<br>涉猎领域十分广泛',
		'effect' => '我有镜湖楼主的头了，我过马路不用怕被汽车撞了！',
		'desc_skills' => '开局装备拥有防弹、防投、防符属性的「楼主头」',
		'energy' => 100,
		'valid' => array(
			'arh' => '楼主头',
			'arhk' => 'DH',
			'arhe' => '81',
			'arhs' => '27',
			'arhsk' => 'GCF',
		)
	),
	171 => array(
		'name' => '纸条挑战者 林苍月',
		'title' => '纸条挑战者',
		'rare' => 'B',
		'pack' => 'Ranmen',
		'desc' => '嗯？好像还有几张没撒完',
		'effect' => '开局时，后四个包裹栏全塞满提示纸条',
		'energy' => 100,
		'valid' => array(
			'itm3' => array('提示纸条A', '提示纸条B', '提示纸条C', '提示纸条D', '提示纸条E', '提示纸条F', '提示纸条G', '提示纸条H', '提示纸条I', '提示纸条J', '提示纸条K', '提示纸条L', '提示纸条M', '提示纸条N', '提示纸条O', '提示纸条P', '提示纸条Q', '提示纸条R', '提示纸条S', '提示纸条T', '提示纸条U', '提示纸条Z'),
			'itmk3' => 'Z',
			'itme3' => '1',
			'itms3' => '1',
			'itmsk3' => '',
			'itm4' => array('提示纸条A', '提示纸条B', '提示纸条C', '提示纸条D', '提示纸条E', '提示纸条F', '提示纸条G', '提示纸条H', '提示纸条I', '提示纸条J', '提示纸条K', '提示纸条L', '提示纸条M', '提示纸条N', '提示纸条O', '提示纸条P', '提示纸条Q', '提示纸条R', '提示纸条S', '提示纸条T', '提示纸条U', '提示纸条Z'),
			'itmk4' => 'Z',
			'itme4' => '1',
			'itms4' => '1',
			'itmsk4' => '',
			'itm5' => array('提示纸条A', '提示纸条B', '提示纸条C', '提示纸条D', '提示纸条E', '提示纸条F', '提示纸条G', '提示纸条H', '提示纸条I', '提示纸条J', '提示纸条K', '提示纸条L', '提示纸条M', '提示纸条N', '提示纸条O', '提示纸条P', '提示纸条Q', '提示纸条R', '提示纸条S', '提示纸条T', '提示纸条U', '提示纸条Z'),
			'itmk5' => 'Z',
			'itme5' => '1',
			'itms5' => '1',
			'itmsk5' => '',
			'itm6' => array('提示纸条A', '提示纸条B', '提示纸条C', '提示纸条D', '提示纸条E', '提示纸条F', '提示纸条G', '提示纸条H', '提示纸条I', '提示纸条J', '提示纸条K', '提示纸条L', '提示纸条M', '提示纸条N', '提示纸条O', '提示纸条P', '提示纸条Q', '提示纸条R', '提示纸条S', '提示纸条T', '提示纸条U', '提示纸条Z'),
			'itmk6' => 'Z',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	172 => array(
		'name' => '富竹次郎',
		'rare' => 'M',
		'pack' => 'Standard Pack',
		'desc' => '熟练的打针玩家',
		'effect' => '称号固定为L5状态',
		'energy' => 0,
		'valid' => array(
			'club' => 15,
		)
	),
	173 => array(
		'name' => '哲学家',
		'rare' => 'A',
		'pack' => 'Way of Life',
		'desc' => '娶个好女人，你会很快乐；<br>娶个坏女人，你会成为哲♂学家',
		'effect' => '开局携带《哲♂学》',
		'energy' => 100,
		'valid' => array(
			'ara' => '《哲♂学》',
			'arak' => 'DA',
			'arae' => '30',
			'aras' => '2',
			'arask' => 'g',
		)
	),
	174 => array(
		'name' => '东方地雷殿',
		'rare' => 'S',
		'pack' => 'Top Players',
		'desc' => 'ACFUN大逃杀最早一批玩家之一，<br>在排行榜上长期名列前茅',
		'effect' => '每经历1次禁区，你就获得1次复活机会。',
		'energy' => 120,
		'valid' => array(
			'skills' => array(
				'518' => array('rmtime' => '0'),
			),
		)
	),
	175 => array(
		'name' => 'G.D.S 研发人员',
		'rare' => 'C',
		'pack' => 'Crimson Swear',
		'desc' => '给他一页ppt他可以讲4个小时',
		'effect' => '开局后四个包裹栏全塞满随机的“原型武器”或者“实验装甲”',
		'energy' => 0,
		'valid' => array(
			'itm3' => array('原型武器P','原型武器K','原型武器G','原型武器C','原型武器D','原型武器F','实验装甲B','实验装甲H','实验装甲A','实验装甲F'),
			'itmk3' => 'X',
			'itme3' => '1',
			'itms3' => '1',
			'itmsk3' => '',
			'itm4' => array('原型武器P','原型武器K','原型武器G','原型武器C','原型武器D','原型武器F','实验装甲B','实验装甲H','实验装甲A','实验装甲F'),
			'itmk4' => 'X',
			'itme4' => '1',
			'itms4' => '1',
			'itmsk4' => '',
			'itm5' => array('原型武器P','原型武器K','原型武器G','原型武器C','原型武器D','原型武器F','实验装甲B','实验装甲H','实验装甲A','实验装甲F'),
			'itmk5' => 'X',
			'itme5' => '1',
			'itms5' => '1',
			'itmsk5' => '',
			'itm6' => array('原型武器P','原型武器K','原型武器G','原型武器C','原型武器D','原型武器F','实验装甲B','实验装甲H','实验装甲A','实验装甲F'),
			'itmk6' => 'X',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	176 => array(
		'name' => 'G.D.S 部门主管',
		'rare' => 'C',
		'pack' => 'Crimson Swear',
		'desc' => '今年我们打算开掉35岁以上的员工',
		'effect' => '凌晨3点打电话给你，你怎么没接？',
		'desc_skills' => '开局装备能用来夺命连环CALL的手机',
		'energy' => 0,
		'valid' => array(
			'wep' => '手机',
			'wepk' => 'WC',
			'wepe' => '200',
			'weps' => '100',
			'wepsk' => 'r^dd75',
		)
	),
	177 => array(
		'name' => 'D级人员',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '如果你能坚持活过这个月，你将获得自由。不，你看，我说“活过”的意思是“和我们合作”，你懂吗？',
		'effect' => '开局位于SCP研究设施',
		'energy' => 0,
		'valid' => array(
			'pls' => '32',
		)
	),
	178 => array(
		'name' => 'wdhwg001',
		'rare' => 'C',
		'pack' => 'Top Players',
		'desc' => '一个水平很高的程序员，对游戏开发颇有一番见解',
		'effect' => '开局装备「wdhwg001的键盘」',
		'energy' => 0,
		'valid' => array(
			'wep' => '「wdhwg001的键盘」',
			'wepk' => 'WG',
			'wepe' => '50',
			'weps' => '50',
			'wepsk' => '',
		)
	),
	179 => array(
		'name' => 'G.D.S 网管',
		'rare' => 'C',
		'pack' => 'Crimson Swear',
		'desc' => '谁他妈又在下毛片？！刚升级的千兆带宽又给占满了！',
		'effect' => '开局随机携带增幅设备、某种电子零件、手机、笔记本电脑、探测器电池中的其中一个',
		'energy' => 0,
		'valid' => array(
			'itm6' => array('增幅设备','某种电子零件','手机','笔记本电脑','探测器电池'),
			'itmk6' => 'X',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	180 => array(
		'name' => 'G.D.S 物业',
		'rare' => 'C',
		'pack' => 'Crimson Swear',
		'desc' => '我们公司下个月一定能把老鼠都消灭',
		'effect' => '已申请采购全套灭鼠装备',
		'energy' => 0,
		'valid' => array(
			'itm4' => array('捕鼠夹','捕鼠笼','捕鼠胶','粘鼠板','电子灭鼠器','灭鼠无人机'),
			'itmk4' => 'TN',
			'itme4' => '25',
			'itms4' => '3',
			'itmsk4' => '',
			'itm5' => array('捕鼠夹','捕鼠笼','捕鼠胶','粘鼠板','电子灭鼠器','灭鼠无人机'),
			'itmk5' => 'TN',
			'itme5' => '25',
			'itms5' => '3',
			'itmsk5' => '',
			'itm6' => array('捕鼠夹','捕鼠笼','捕鼠胶','粘鼠板','电子灭鼠器','灭鼠无人机'),
			'itmk6' => 'TN',
			'itme6' => '25',
			'itms6' => '3',
			'itmsk6' => '',
		)
	),
	181 => array(
		'name' => '幽灵 帕比丝麦尔',
		'title' => '幽灵',
		'rare' => 'S',
		'pack' => 'Event Bonus',
		'desc' => '　　说到幽灵，Big 52的幽灵传说仍在继续，尽管它现在比起事实，更像是个噩梦夜的鬼故事。据说，当太阳落山、月亮升起以后，会有一个鬼影踩着滑板车在大路上游荡，随风而来，寂静无声。它直奔坏蛋而去，追索着奴隶贩子、劫掠者和阴谋家的性命。至少有两队劫掠者真的死在了大路上。他们外表毫发无伤，武器上膛、如临大敌，却依然横尸街头，原因成谜，凶手却没有留下一丝线索。说了这么多，我只想问你一个问题…',
		'effect' => '<span class="red b">你 相 信 有 幽 灵 吗 ？</span>',
		'desc_skills' => '称号固定为亡灵骑士，复活技能替换为「幽灵」并获得技能「纯洁」
		<br>「幽灵」：你被战斗和陷阱杀死时必然复活，冷却时间6分钟
		<br>「纯洁」：你先制攻击没有杀过玩家的角色时造成的伤害减半；杀死你的角色将诅咒缠身',
		'bigdesc' => 1,
		'energy' => 150,
		'valid' => array(
			'club' => '24',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0', 
				'24' => '0', 
				'519' => '0', 
				'61' => '0', 
				'62' => '0', 
				'60' => '0', 
				'64' => '0', 
				'63' => '0',
				'520' => '0',
			),
		)
	),
	182 => array(
		'name' => '简单',
		'title' => '新米玩家',
		'rare' => 'C',
		'pack' => 'hidden',
		'desc' => '简单模式，适合到幻界游玩的旅游者。',
		'effect' => '对其他玩家造成的伤害降低，对NPC造成的伤害升高；其他NPC和玩家对你的伤害降低。',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'1004' => '1', 
				'1005' => '0',
			),
		)
	),
	183 => array(
		'name' => '通常',
		'title' => '通常玩家',
		'rare' => 'B',
		'pack' => 'hidden',
		'desc' => '通常模式，适合所有的挑战者。',
		'effect' => '和普通游戏模式一样，不做数值修正。',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'1004' => '2', 
				'1005' => '0',
			),
		)
	),
	184 => array(
		'name' => '困难',
		'title' => '认真玩家',
		'rare' => 'A',
		'pack' => 'hidden',
		'desc' => '认真模式，你是励志认真修复的异变处理人！',
		'effect' => '对玩家和NPC造成的伤害降低，NPC和其他玩家对你造成的伤害升高。',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'1004' => '3', 
				'1005' => '0',
			),
		)
	),
	185 => array(
		'name' => '疯狂',
		'title' => '疯狂玩家',
		'rare' => 'S',
		'pack' => 'hidden',
		'desc' => '疯狂模式，你究竟是来干啥的？',
		'effect' => 'ERROR 40404 侦测到迷之错误，该难度描述无法找到！',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'1004' => '4', 
				'1005' => '0',
			),
		)
	),
	186 => array(
		'name' => '超魔理沙',
		'rare' => 'S',
		'pack' => 'Event Bonus',
		'desc' => '晚上好，各位<br>刚刚这里好像有寿命论的气息啊，<br>真是被看扁了',
		'effect' => '爱的力量，是无限的！',
		'desc_skills' => '开局称号为超能力者，但技能为肌肉兄贵技能「活化」「金刚」「破巧」和根性兄贵技能「毅重」「代偿」「不屈」和「浴血」
		<br>「活化」：每次攻击基础攻击+1点，每次被攻击基础防御+1点
	  <br>「金刚」：每100点基础防御降低所受物理固定伤害或爆炸伤害1点，可以切换，可以升级
	  <br>「破巧」：每局游戏限2次，这次攻击物理伤害+65%，这次战斗中敌方所有技能无效
		<br>「毅重」：开启后，你在战斗中视为只拥有物防、属防和控伤属性
		<br>「代偿」：战斗时失去200生命就能让生命上限增加1点，可以升级
		<br>「不屈」：如果受到的战斗伤害不超过你最大HP的一半，你不会因为这次伤害而死
		<br>「浴血」：战斗中消耗生命值向对方附加等量的物理伤害，可以消耗生命代替部分怒气发动',
		'energy' => 120,
		'valid' => array(
			'club' => '9',
			'skills' => array(
				'29' => '0', 
				'39' => '0', 
				'12' => '0', 
				'40' => '0', 
				'44' => '0', 
				'46' => '0', 
				'28' => '0', 
				'267' => '0', 
				'268' => '0',
				'269' => '0',
			),
		)
	),
	187 => array(
		'name' => '清理大师',
		'rare' => 'B',
		'pack' => 'Top Players',
		'desc' => '我已经是最强赤木茂了',
		'effect' => '我还差赤木茂的内裤',
		'desc_skills' => '开局装备赤木茂全套，但是还差内裤',
		'energy' => 100,
		'valid' => array(
			'wep' => '赤木茂的娃娃',
			'wepk' => 'WP',
			'wepe' => '76',
			'weps' => '5',
			'wepsk' => '',
			'arb' => '赤木茂的衣服',
			'arbk' => 'DB',
			'arbe' => '76',
			'arbs' => '5',
			'arbsk' => '',
			'arh' => '',
			'arhk' => '',
			'arhe' => '0',
			'arhs' => '0',
			'arhsk' => '',
			'ara' => '赤木茂的杯子',
			'arak' => 'DA',
			'arae' => '76',
			'aras' => '5',
			'arask' => '',
			'arf' => '赤木茂的挂画',
			'arfk' => 'DF',
			'arfe' => '76',
			'arfs' => '5',
			'arfsk' => '',
			'art' => '赤木茂的帆布袋',
			'artk' => 'A',
			'arte' => '76',
			'arts' => '5',
			'artsk' => 'H',
		)
	),
	188 => array(
		'name' => '歌神 mtkkk',
		'title' => '歌神',
		'rare' => 'B',
		'pack' => 'Top Players',
		'desc' => '很喜欢在雏菊开音趴的小仓唯粉丝',
		'effect' => '开局携带一张随机歌词卡片',
		'energy' => 100,
		'valid' => array(
			'itm6' => Array('小苹果', 'Alicemagic', 'Crow Song', '驱寒颂歌', '雨だれの歌', '快说小仓唯唱歌贼！好！听！', '黄鸡之歌'),
			'itmk6' => 'ss',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => 'z',
		)
	),
	189 => array(
		'name' => '锁血挑战者',
		'rare' => 'C',
		'pack' => 'Ranmen',
		'desc' => '我相信着我的卡组！',
		'effect' => '开局HP为1点，<br>额外携带1份游戏王卡牌包',
		'energy' => 0,
		'valid' => array(
			'hp' => 1,
			'itm6' => '游戏王卡包',
			'itmk6' => 'ygo',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	190 => array(
		'name' => 'ycNaN',
		'rare' => 'A',
		'pack' => 'Event Bonus',
		'desc' => '过去经历一切不明的女骇客，<br>其typing能力就算在糟糕级骇客中也实际强大！',
		'effect' => '开局经验、金钱和怒气都增加77点。<br>如果你上一次操作在程序执行时<br>没有顺利完成，你获得7点经验和全系熟练，还会给这个游戏的天然呆程序员发一封站内信。',
		'energy' => 100,
		'valid' => array(
			'money' => '+77',
			'exp' => '+77',
			'rage' => '+77',
			'skills' => array(
				'528' => '0', 
			),
		),
		'ignore_global_ach' => 1,//不参与终生成就判定
	),
	191 => array(
		'name' => 'G.D.S 女秘书',
		'rare' => 'C',
		'pack' => 'Crimson Swear',
		'desc' => '明天见总裁，要穿得诱惑一点',
		'effect' => '总裁怎么是女生？',
		'desc_skills' => '开局装备带有「热恋」属性的防具',
		'energy' => 0,
		'valid' => array(
			'arb' => '性感女内衣',
			'arbk' => 'DA',
			'arbe' => '5',
			'arbs' => '15',
			'arbsk' => 'l',
		)
	),
	192 => array(
		'name' => '孤魂挑战者',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '他很喜欢满身孤魂的感觉',
		'effect' => '开局全身装备埃克法-孤魂',
		'energy' => 0,
		'valid' => array(
			'wep' => '埃克法-孤魂',
			'wepk' => 'WP',
			'wepe' => '25',
			'weps' => '1',
			'wepsk' => 'cZ',
			'arb' => '埃克法-孤魂',
			'arbk' => 'DB',
			'arbe' => '5',
			'arbs' => '25',
			'arbsk' => 'zZ',
			'arh' => '埃克法-孤魂',
			'arhk' => 'DH',
			'arhe' => '5',
			'arhs' => '25',
			'arhsk' => 'zZ',
			'ara' => '埃克法-孤魂',
			'arak' => 'DA',
			'arae' => '5',
			'aras' => '25',
			'arask' => 'zZ',
			'arf' => '埃克法-孤魂',
			'arfk' => 'DF',
			'arfe' => '5',
			'arfs' => '25',
			'arfsk' => 'zZ',
			'art' => '埃克法-孤魂',
			'artk' => 'TN',
			'arte' => '25',
			'arts' => '1',
			'artsk' => 'cZ',
		)
	),
	193 => array(
		'name' => 'G.D.S 社员',
		'rare' => 'C',
		'pack' => 'Crimson Swear',
		'desc' => '刚入职，领导就让我当法人，我深受感动',
		'effect' => '开局携带一张ID卡',
		//'desc_skills' => '',
		'energy' => 0,
		'valid' => array(
		  'itm6' => Array('社畜专用的ID卡', '社员砖用的ID卡', '社长专用的ID卡', '社恐专用的ID卡', '社员专甩的ID卡',
		  '社员不用的ID卡', '社死专用的ID卡', '社保专用的ID卡', '社精专用的ID卡', '社会专用的ID卡', '社戏专用的ID卡',
		  '社员专用的IC卡', '社员专用的IP卡', '社员专用的IQ卡', '社员专用的1D卡', '社员专用的IO卡', '社员专用的lD卡', '社员专用的|D卡', '社员专用的ＩＤ卡',
		  '社元专用的ID卡', '社员专角的ID卡', '社贡专用的ID卡', '社员专卖的ID卡', '社员专享的ID卡', '社员专业的ID卡', 
		  '社员专精的ID卡', '涩员专用的ID卡', '杜员专用的ID卡', '壮员专用的ID卡', '社员专月的ID卡',),
			'itmk6' => 'Y',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	194 => array(
		'name' => '除错挑战者',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '年轻触手为打除错竟入场把萌新活活打死',
		'effect' => '获得除错模式技能「整备」「谨慎」「阴谋」的其中随机一个',
		'desc_skills' => '「整备」：恢复满HP、SP，并治好所有伤口和异常状态，可以升级来减少冷却时间
		<br>「谨慎」：你受到的陷阱伤害减少，可以升级。你接收其他玩家的道具时有概率拒绝
		<br>「阴谋」：你放置的陷阱伤害上升，可以升级',
		'energy' => 100,
		'valid' => array(
		  'rand_skills' => array(
		  	Array(
			  	'426' => '0', 
					'428' => '0', 
					'429' => '0',
					'rnum' => 1,
		  	)				
			),
		)
	),
	195 => array(
		'name' => '费马',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '我超，我想到一张绝妙的好卡',
		'effect' => '可惜效果栏太小，写不下',
		'desc_skills' => '获得最强大脑技能「证伪」：战斗中敌人没有正常生效的减半防御属性类型将永久对你无效',
		'energy' => 0,
		'valid' => array(
		  'skills' => array(
				'243' => '0', 
			),
		)
	),
	196 => array(
		'name' => '低维生物',
		'rare' => 'A',
		'pack' => 'Top Players',
		'desc' => '“虫子，在这个宇宙中，对于一个种族文明程度的统一度量就是这个种族所进入的空间维度，只有进入六维以上空间的种族才具备加入文明大家庭的起码条件，我们尊敬的神的一族已能够进入十一维空间。<br>吞食帝国已能在实验室中小规模地进入四维空间，只能算是银河系中一个未开化的原始群落……”',
		'effect' => '“而你们在神的眼里，<br>也就是杂草和青苔一类的东西！”',
		'desc_skills' => '游戏中仅1次，如果你的武器效果值不小于7000点，你可以发动「降维」从而把自己转移到另外一场随机的游戏中。<br>降维后你会位于【三体星】并手持 维度跌落「二向箔」，但其他装备道具和金钱都会丢失。<br>你不会降维到除错、伐木、教程和试炼模式，也不会降维到开局不满15分钟的游戏',
		'bigdesc' => 1,
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'530' => '0', 
			),
		)
	),
	197 => array(
		'name' => '萤火抑智',
		'rare' => 'C',
		'pack' => 'Best DOTO',
		'desc' => '今天吃鸡腿',
		'effect' => '在入场讯息和当前幸存页面，<br>你的角色名和学号互换',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'533' => '1', 
			),
		)
	),
	198 => array(
		'name' => '非常非常二',
		'rare' => 'B',
		'pack' => 'Top Players',
		'desc' => 'IG空间，全称 <ruby>虚数几何空间<rt>Imaginary Geometry</rt></ruby>，<br>只有极少数擅长钻空子的能力者才能把物质自由地送到那里',
		'effect' => '获得技能「空子」：你能把道具存进异空间或者从中取出。<br>最多同时储存两个道具',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'534' => '2', 
			),
		)
	),
	199 => array(
		'name' => '武侠强者 李天明',
		'title' => '武侠强者',
		'rare' => 'A',
		'pack' => 'Event Bonus',
		'desc' => '<span class="vermilion b">“对一一五来说，面前这四个触手比十亿个杀人魔更恐怖…更可怕呀！”</span>',
		'effect' => '开局获得「红杀铁剑」与额外的合成知识。此外，涉及到你的描述伤害的进行状况都改用强者语',
		'energy' => 100,
		'valid' => array(
			'itm6' => '【红杀铁剑】',
			'itmk6' => 'WK',
			'itme6' => '80',
			'itms6' => '60',
			'itmsk6' => 'u',
			'skills' => array(
				'531' => '0', 
			),
		)
	),
	
	200 => array(
		'name' => '幻影斗将神 S.A.S',
		'title' => '幻影斗将神',
		'rare' => 'A',
		'pack' => 'Balefire Rekindle',
		'desc' => '“只要能为我的族人复仇，哪怕我<br>堕入永劫地狱也在所不惜！”',
		'effect' => '获得技能「嫉恶」：攻击玩家时，其每杀过1名玩家，受到的最终伤害+21%',
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
		'effect' => '获得技能「无垠」：战斗中死亡时有100%概率复活，但之后因此复活的概率减半',
		//：双方攻击结束时，如果你的HP<1，则有30%概率变为1。此技能每发动1次，这一概率减半。
		'energy' => 120,
		'valid' => array(
			'skills' => array(
				'486' => '2', 
			),
		)
	),
	202 => array(
		'name' => '通灵冒险家 星海',
		'title' => '通灵冒险家',
		'rare' => 'A',
		'pack' => 'Balefire Rekindle',
		'desc' => '“你住酒店时有没有第一时间确认<br>逃生通道的习惯？没有吧？我有。”',
		'effect' => '获得技能「后路」：你行动时若因战斗或陷阱导致生命值不足一半，则会自动使用回复道具',
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
		'effect' => '获得技能「神眷」：战斗中受到的异常状态反弹给敌方，行动时不会因为异常状态受到伤害',
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
		'effect' => '获得技能「空想」：每60秒可以获得1个类别、效果、耐久和属性皆随机的「空想道具」',
		//：每60秒可以获得1个类别、效果、耐久和属性皆随机的「空想道具」，其效和耐合计值不超过发动时支付的体力。
		'energy' => 150,
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
		'desc_skills' => '「神速」：战斗中基础攻击力+10%，每次攻击有50%概率让基础攻击力+1，<br>有额外65%的概率反击。',
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
				'80' => '0',
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
		'effect' => '获得技能「时停」，<br>但不能赢得「最后幸存」胜利',
		'desc_skills' => '「时停」：消耗30点怒气，能停止整个幻境的时间3秒钟，冷却时间30秒。对拥有时间能力的角色无效',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'500' => '0', 
				'501' => '0',
			),
		)
	),
	208 => array(
		'name' => '『无我』',
		'ruby' => 'Absentia',
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
		'desc' => '<span class="evergreen b">“虚拟幻境犹如一道曙光，<br>照亮了这个万物沉沦的黑暗年代。<br>我会守护这道希望之光，<br>直到生命的最后一刻。”</span>',
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
		'real_rare' => 'B',//真实的爆率
		'pack' => 'Way of Life',
		'desc' => '一月霸权动漫<br>《pop子与pipi美的日常》主角',
		'effect' => '「生气了吗……？」',
		'desc_skills' => '“这可是一张不需要充能的S卡。”<br>',
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
	211 => array(
		'name' => '卖萌女神 一一五',
		'title' => '卖萌女神',
		'rare' => 'S',
		'pack' => 'Balefire Rekindle',
		'desc' => '身为主播，运气是非常重要的。<br>这位朋友问我的运气？你觉得呢？',
		'effect' => '获得技能「胜天」：你的减半防御和抹消类属性不会自然失效，控伤失效和被贯穿概率减半',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'510' => '1', 
			),
		)
	),
	212 => array(
		'name' => '只因',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '跳、唱、rap、篮球',
		'effect' => '你干嘛，哎哟',
		'desc_skills' => '开局获得5点生命、最大生命、歌魂和最大歌魂，并装备篮球和背带裤',
		'energy' => 0,
		'valid' => array(
			'mhp' => '+5',
			'hp' => '+5',
			'mss' => '+5',
			'ss' => '+5',
			'wep' => '篮球',
			'wepk' => 'WC',
			'wepe' => '12',
			'weps' => '1',
			'wepsk' => '',
			'arb' => '背带裤',
			'arbk' => 'DB',
			'arbe' => '5',
			'arbs' => '15',
			'arbsk' => '',
		)
	),
	213 => array(
		'name' => '反思怪',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '你应该反思',
		'effect' => '获得拆弹专家技能「反思」',
		'desc_skills' => '「反思」：使用爆系武器时，即使攻击没有命中，也可以获得1点经验值',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'213' => '0', 
			),
		)
	),
	214 => array(
		'name' => '源神',
		'rare' => 'A',
		'pack' => 'Cyber Zealots',
		'desc' => '你说得对，但是《源神》是由上千主上自主研发的一款全新开放世界冒险游戏。游戏发生在一个被称作「源数网络」的幻想世界，在这里被神选中的人将被授予「源数之壁」，引导源批之力。你将扮演一位名为「源数直上」的神秘角色，在自由的旅行中邂逅性格各异、能力独特的源数之门们，和它们一起击败强敌，找回不存在的亲人的同时，逐步发掘「源数代码」的真相。',
		'effect' => '获得技能「攻击」',
		'desc_skills' => '「攻击」：你的攻击性有待提高。<br>每局最多4次，你可以在攻击结束时让你的的基础攻击力永久翻倍',
		'bigdesc' => 1,
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'527' => '0', 
			),
		)
	),
	215 => array(
		'name' => '超天酱',
		'rare' => 'B',
		'pack' => 'Cyber Zealots',
		'desc' => '✝︎ 当代互联网小天使，堂堂降临! ✝︎',
		'effect' => '即将化身为小天使的地雷',
		'desc_skills' => '开局携带一枚效果值为800的陷阱，但最大生命值只有正常的一半',
		'energy' => 100,
		'valid' => array(
		  'hp' => '-200',
		  'mhp' => '-200',
			'itm6' => '超天新龙·异色眼革命龙 ★12',
			'itmk6' => 'TN12',
			'itme6' => '800',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	216 => array(
		'name' => '吧友',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '经验+3',
		'effect' => '告辞',
		'energy' => 0,
		'valid' => array(
		  'exp' => '+3',
		)
	),
	217 => array(
		'name' => '章鱼猫',
		'rare' => 'B',
		'pack' => 'Cyber Zealots',
		'desc' => '一种神奇的电子界生物，擅长版本控制和同性交友',
		'effect' => '开局携带建立分叉和拉取请球',
		//'desc_skills' => '',
		'energy' => 100,
		'valid' => array(
		  'wep' => '建立分叉',
			'wepk' => 'WK',
			'wepe' => '133',
			'weps' => '7',
			'wepsk' => 'fOrk',
			'itm6' => '拉取请球',
			'itmk6' => 'DH',
			'itme6' => '133',
			'itms6' => '7',
			'itmsk6' => 'g',
		)
	),
	218 => array(
		'name' => '撸贷人',
		'rare' => 'M',
		'pack' => 'Cyber Zealots',
		'desc' => '凭本事借的钱，为什么要还？',
		'effect' => '他因为撸小贷被限制消费了',
		'desc_skills' => '初始金额为100元，但获取金额时不能超过上限。可以消耗技能点还款来解除限制。',
		'energy' => 0,
		'valid' => array(
		  'money' => '+80',
			'skills' => array(
				'495' => '0', 
			),
		)
	),
	219 => array(
		'name' => '福瑞控',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '24小时穿着等身大熊装的福瑞控',
		'effect' => '开局装备【智代专用熊装】',
		'desc_skills' => '但是没有防御属性',
		'energy' => 0,
		'valid' => array(
		  'arb' => '【智代专用熊装】',
			'arbk' => 'DB',
			'arbe' => '80',
			'arbs' => '20',
			'arbsk' => '',
		)
	),
	220 => array(
		'name' => '钓鱼吧老哥',
		'rare' => 'C',
		'pack' => 'Ranmen',
		'desc' => '不知道，大概40斤吧',
		'effect' => '开局装备钓鱼杆并携带他的收获',
		//'desc_skills' => '但是没有防御属性',
		'energy' => 0,
		'valid' => array(
		  'ara' => Array('钓鱼竿','钓鱼竿','钓鱼竿','钓鱼竿','钓鱼竿','钓鱼竿','钓鱼竿','钓鱼竿','钓鱼竿','钓鱼竿','钓鱼竿','钓鱼竿','钓鱼竿','钓鱼竿','钓鱼竿','钓鱼竿','钓鱼竿','钓鱼竿','钓鱼竿','《小黄的钓鱼竿》'),
			'arak' => 'DA',
			'arae' => '50',
			'aras' => '70',
			'arask' => '',
			'itm6' => Array('垃圾','光盘','破鞋','塑料袋','石头','树枝','骨头','原型武器K','鲨鱼鳍','凸眼鱼'),
			'itmk6' => Array('X','Y','HH','HS','HB','PB','PB2'),
			'itme6' => '70',
			'itms6' => '5',
			'itmsk6' => 'z',
		)
	),
	221 => array(
		'name' => '221',
		'rare' => 'B',
		'pack' => 'Cyber Zealots',
		'desc' => '上古著名职人',
		'effect' => '开局携带『和谐你全家』',
		//'desc_skills' => '',
		'energy' => 100,
		'valid' => array(
		  'wep' => '『和谐你全家』',
			'wepk' => 'WP',
			'wepe' => '22',
			'weps' => '1',
			'wepsk' => 'uiewp',
		)
	),
	222 => array(
		'name' => '门锁',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '拍完记得装回去',
		'effect' => '开局携带门锁',
		'desc_skills' => '门锁可以变为探测仪器，但是装不回去了',
		'energy' => 0,
		'valid' => array(
		  'wep' => '门锁',
			'wepk' => 'WP',
			'wepe' => '50',
			'weps' => '1',
			'wepsk' => 'j',
		)
	),
	223 => array(
		'name' => '汽车人',
		'rare' => 'B',
		'pack' => 'Cyber Zealots',
		'desc' => '新能源汽车人才有机会直接落户上海',
		'effect' => '你开局携带的面包的名字变为电池，矿泉水的名字变为探测器电池',
		'energy' => 0,
		'valid' => array(
		  'itm1' => '电池',
		  'itm2' => '探测器电池',
		)
	),
	224 => array(
		'name' => '翻唱职人',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '弹幕网站上翻唱名曲毁耳不倦的职人',
		'effect' => '开局装备销魂之歌',
		'desc_skills' => '还有ACFUN的账号',
		'energy' => 0,
		'valid' => array(
		  'wep' => '销魂之歌',
			'wepk' => 'WK',
			'wepe' => '50',
			'weps' => '50',
			'wepsk' => '',
			'art' => 'ACFUN的账号',
			'artk' => 'A',
			'arte' => '20',
			'arts' => '10',
			'artsk' => Array('l','g'),
		)
	),
	225 => array(
		'name' => '字幕职人',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '弹幕网站上把弹幕当积木摆的职人',
		'effect' => '开局装备神字幕',
		'desc_skills' => '还有ACFUN的账号',
		'energy' => 0,
		'valid' => array(
		  'wep' => '神字幕',
			'wepk' => 'WC',
			'wepe' => '50',
			'weps' => '50',
			'wepsk' => '',
			'art' => 'ACFUN的账号',
			'artk' => 'A',
			'arte' => '20',
			'arts' => '10',
			'artsk' => Array('l','g'),
		)
	),
	226 => array(
		'name' => '搬运职人',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '弹幕网站上搬运其他网站视频的职人',
		'effect' => '开局装备搬运之拳',
		'desc_skills' => '还有ACFUN的账号',
		'energy' => 0,
		'valid' => array(
		  'wep' => '搬运之拳',
			'wepk' => 'WP',
			'wepe' => '50',
			'weps' => '50',
			'wepsk' => '',
			'art' => 'ACFUN的账号',
			'artk' => 'A',
			'arte' => '20',
			'arts' => '10',
			'artsk' => Array('l','g'),
		)
	),
	227 => array(
		'name' => '专业喷子',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '一个普通的喷子，全身上下只有嘴是硬的',
		'effect' => '开局装备嘴炮',
		'desc_skills' => '还有ACFUN的账号',
		'energy' => 0,
		'valid' => array(
		  'wep' => '嘴炮',
			'wepk' => 'WG',
			'wepe' => '50',
			'weps' => '50',
			'wepsk' => '',
			'art' => 'ACFUN的账号',
			'artk' => 'A',
			'arte' => '20',
			'arts' => '10',
			'artsk' => Array('l','g'),
		)
	),
	228 => array(
		'name' => '小鬼',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '他很热衷于网络80',
		'effect' => '开局装备网暴',
		'desc_skills' => '他也有ACFUN的账号',
		'energy' => 0,
		'valid' => array(
		  'wep' => '网暴',
			'wepk' => 'WD',
			'wepe' => '50',
			'weps' => '50',
			'wepsk' => '',
			'art' => 'ACFUN的账号',
			'artk' => 'A',
			'arte' => '20',
			'arts' => '10',
			'artsk' => Array('l','g'),
		)
	),
	229 => array(
		'name' => '车万人',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '车万人下雨不用伞',
		'effect' => '开局装备符卡',
		'desc_skills' => '他不屑于在ACFUN注册账号',
		'energy' => 0,
		'valid' => array(
		  'wep' => '符卡',
			'wepk' => 'WF',
			'wepe' => '50',
			'weps' => '50',
			'wepsk' => '',
		)
	),
	230 => array(
		'name' => '老八',
		'rare' => 'M',
		'pack' => 'Cyber Zealots',
		'desc' => '奥利给！干了兄弟们！',
		'effect' => '你可以吃翔来表达对满场沙包的不屑',
		'energy' => 0,
		'valid' => array(
		  'itm6' => '『我是说在座的各位都是垃圾』',
			'itmk6' => 'Y',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	231 => array(
		'name' => '卡片男',
		'rare' => 'B',
		'pack' => 'Ranmen',
		'desc' => '看，这个男人叫小帅，<br>他捡到了一张神秘的小卡片',
		'effect' => '开局携带一份随机的卡牌包',
		'energy' => 100,
		'valid' => array(
		  'itm6' => '卡牌包',
			'itmk6' => Array('VO3', 'VO2', 'VO2', 'VO2', 'VO9', 'VO9', 'VO9', 'VO9', 'VO9', 'VO9'),
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	232 => array(
		'name' => '驴友',
		'rare' => 'M',
		'pack' => 'Cyber Zealots',
		'desc' => '他觉得自己很懂野外生存',
		'effect' => '开局不携带面包和矿泉水',
		'energy' => 0,
		'valid' => array(
		  'itm1' => '我来幻境是我的自由',
			'itmk1' => 'X',
			'itme1' => '1',
			'itms1' => '1',
			'itmsk1' => '',
			'itm2' => '你们怎么还不来救我？',
			'itmk2' => 'X',
			'itme2' => '1',
			'itms2' => '1',
			'itmsk2' => '',
		)
	),
	233 => array(
		'name' => '阿林百人众',
		'rare' => 'A',
		'pack' => 'Ranmen',
		'desc' => '据说林无月前两年已经死了，<br>现在管理幻境的都是她的亲友团<br>「阿林百人众」。<br>她临死前给每个人发了一个小本子，里面是一百多页如何扮演她的心得。',
		'effect' => '开局能随机复制并获取场上存活NPC的一项技能（包括称号技能）',
		'energy' => 100,
		'valid' => array(
		  'skills' => array(
				'529' => '2', 
			),
		)
	),
	234 => array(
		'name' => '黑莲花',
		'rare' => 'A',
		'pack' => 'Cyber Zealots',
		'desc' => 'All their base are belong to us!',
		'effect' => '<s>法力+3</s><br>获得边缘行者技能「网瘾」、「破解」及「探测」',
		'desc_skills' => '「网瘾」：你干扰禁区的成功率为95%且完全无风险
		<br>「破解」：通过消耗特定的物品来破解游戏系统，每次破解成功都会获得奖励
		<br>「探测」：消耗1个技能点可以进行一次广域探测',
		'energy' => 100,
		'valid' => array(
		  'skills' => array(
				'233' => '0', 
				'234' => '0', 
				'235' => '0', 
			),
		)
	),
	235 => array(
		'name' => '幻境助手 艾茵',
		'title' => '幻境助手',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '哈啰？这里是幻境全息助手艾茵，<br>要探索幻境请务必找我哟！',
		'effect' => '开局携带一个银色盒子',
		'energy' => 0,
		'valid' => array(
		  'itm6' => '黯淡的银色盒子',
			'itmk6' => 'p2',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	236 => array(
		'name' => '模因测试员',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '今天没吃药，感觉自己萌萌哒',
		'effect' => '开局携带15个模因原液',
		'energy' => 0,
		'valid' => array(
			'itm4' => '模因原液',
			'itmk4' => Array('MA','MD','MH','MS','ME','MV'),
			'itme4' => '1',
			'itms4' => '5',
			'itmsk4' => '',
			'itm5' => '模因原液',
			'itmk5' => Array('MA','MD','MH','MS','ME','MV'),
			'itme5' => '1',
			'itms5' => '5',
			'itmsk5' => '',
			'itm6' => '模因原液',
			'itmk6' => Array('MA','MD','MH','MS','ME','MV'),
			'itme6' => '1',
			'itms6' => '5',
			'itmsk6' => '',
		)
	),
	237 => array(
		'name' => '『零·虚光』',
		'rare' => 'S',
		'pack' => 'Cyber Zealots',
		'desc' => '『时空护卫』的中坚力量之一，<br>能轻易记住其他超能力者的超能力，并用自己的方式施展出来。<br><br><span class="seagreen b">“就算在那群固执的疯子里<br>也是最固执而最疯的一个。”</span><br>——林苍月',
		'effect' => '随机发动一张S级卡片的效果',
		'energy' => 100,
		'valid' => array(
			'cardchange' => Array(
				'S_odds' => 100,
				'allow_EB' => true,
				'forced' => Array(),//无视概率强制加入选择的卡
				'ignore_cards' => Array(300)//机制上必定选不到自己，这里可以放其他不想被选到的卡
			)
		)
	),
	238 => array(
		'name' => '『黑衣少女』',
		'rare' => 'S',
		'pack' => 'Cyber Zealots',
		'desc' => '接管了整个林氏集团的神秘少女。<br>虽然她自称只是林无月的女儿，也没有干预幻境的运转，但她显露出的卓越洞察力和深不可测的举止，都表明她并不是凡庸之辈。<br><br><span class="ltcrimson b">“我们的敌人比预想的更加麻烦。”</span><br>——红暮',
		'effect' => '只要你入场，连斗判定人数增加200名',
		'energy' => 200,
		'valid' => array(
			'gamevars' => Array(
				'combonum' => '+200',
			)
		)
	),
	239 => array(
		'name' => '挂壁',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '小透不算开',
		'effect' => '获得宛如疾风技能「天眼」：在战斗界面中你可以查看到对手的具体数值信息，且无视雾天影响',
		//'desc_skills' => '',
		'energy' => 0,
		'valid' => array(
		  'skills' => array(
				'252' => '0', 
			),
		)
	),
	240 => array(
		'name' => '网络爬虫',
		'rare' => 'B',
		'pack' => 'Ranmen',
		'desc' => '爬吗？',
		'effect' => '爬',
		'desc_skills' => '开局能随机复制并获得场上存活的NPC身上的一件道具',
		'energy' => 100,
		'valid' => array(
		  'skills' => array(
				'529' => '1', 
			),
		)
	),
	241 => array(
		'name' => '海丽丝',
		'ruby' => 'H.A.I.Lice',
		'rare' => 'S',
		'pack' => '東埔寨Protoject',
		'desc' => '「東埔寨Protoject」的创作者，独自搭建了庞大幻想世界的奇异少女，最喜欢喝的东西是东之国的啤酒。<br>谁也没有在线下见过她，许多爱好者据此脑补她并非人类，而是一个<br>拥有实体的高等人工智能。<br><br>但她的真身其实是一名妖怪。<br>在这个赛博末法时代隐居起来的、<br>真正的妖怪。',
		'effect' => '随机发动一张「東埔寨Protoject」卡包的S、A或B卡的效果',
		'desc_skills' => 'S卡、A卡和B卡的几率分别为25%、30%和45%',
		'bigdesc' => 1,
		'energy' => 50,
		'valid' => array(
			'cardchange' => Array(
				'S_odds' => 25,
				'A_odds' => 35,
				'B_odds' => 45,
				'packlimit' => '東埔寨Protoject',
				'allow_EB' => false,
				'forced' => Array(),//无视概率强制加入选择的卡
				'ignore_cards' => Array(344)//机制上必定选不到自己，这里可以放其他不想被选到的卡
			)
		)
	),
	242 => array(
		'name' => '刀哥蝙蝠侠',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '我上回有活儿，咬个打火机，封号一个月，钱都扣了',
		'effect' => '你有活儿，你玩',
		'desc_skills' => '开局携带打火机',
		'energy' => 0,
		'valid' => array(
		  'arh' => '打火机',
			'arhk' => 'X',
			'arhe' => '1',
			'arhs' => '1',
			'arhsk' => '',
		)
	),
	243 => array(
		'name' => '求生专家',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '我先叠个甲',
		'effect' => '开局防御力+200',
		//'desc_skills' => '',
		'energy' => 0,
		'valid' => array(
		  'def'=> '+200',
		)
	),
	244 => array(
		'name' => '社博朋克',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '芯片又不能当饭吃',
		'effect' => '开局携带可以吃的电子零件',
		//'desc_skills' => '',
		'energy' => 0,
		'valid' => array(
		  'itm6' => '某种电子零件',
			'itmk6' => Array('HB','HB','HB','HB','HB','HB','HB','HB','HB','PB2'),
			'itme6' => '199',
			'itms6' => '5',
			'itmsk6' => '',
		)
	),
	245 => array(
		'name' => '网抑云',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '这么晚还在打大逃杀，<br>你一定也很寂寞吧',
		'effect' => '开局携带3个寂寞',
		//'desc_skills' => '',
		'energy' => 0,
		'valid' => array(
			'itm4' => '寂寞',
			'itmk4' => Array('WP','WK','WD','WC','WG','WF','WB','PB2','DB','DH','DA','DF','A'),
			'itme4' => '76',
			'itms4' => '5',
			'itmsk4' => '',
			'itm5' => '寂寞',
			'itmk5' => Array('WP','WK','WD','WC','WG','WF','WB','PB2','DB','DH','DA','DF','A'),
			'itme5' => '76',
			'itms5' => '5',
			'itmsk5' => '',
		  'itm6' => '寂寞',
			'itmk6' => Array('WP','WK','WD','WC','WG','WF','WB','PB2','DB','DH','DA','DF','A'),
			'itme6' => '76',
			'itms6' => '5',
			'itmsk6' => '',
		)
	),
	246 => array(
		'name' => '豪有根',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '神灵庙出产的人形大萝卜，有两个尖',
		'effect' => '开局携带大量箭矢',
		//'desc_skills' => '',
		'energy' => 0,
		'valid' => array(
		  'itm6' => '雷矢「元兴寺的旋风」',
			'itmk6' => 'GA',
			'itme6' => '1',
			'itms6' => '99',
			'itmsk6' => 'e',
		)
	),
	247 => array(
		'name' => '女骑士',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '咕、杀了我',
		'effect' => '获得技能「挖矿」：你在休息、治疗和静养时无法得到恢复，改为每秒有10%概率获得1元',
		'energy' => 0,
		'valid' => array(
		  'skills' => array(
				'535' => '0',
			),
		)
	),
	248 => array(
		'name' => '臭名昭著的B.I.G',
		'rare' => 'S',
		'pack' => 'hidden',
		'desc' => '被BIG吃掉了！',
		'effect' => '因为已经是尸体，所以也无法再杀死他了！',
		'desc_skills' => '你被杀死时将在场上复制一个与你数据完全相同的NPC。你每额外获得这张卡一次，复制NPC的数目也会额外增加一个',
		'energy' => 0,
		'valid' => array(//todo
		  
		)
	),
	249 => array(
		'name' => '✦复燃的烽火',
		'rare' => 'B',
		'pack' => 'Cyber Zealots',
		'desc' => '神秘出现在大逃杀幻境里的一群<br>可爱萝莉',
		'effect' => '开局携带能挡下所有攻击的防具，但是只能挡一点点',
		//'desc_skills' => '',
		'energy' => 100,
		'valid' => array(
		  'arh' => '✦烽火之恋',
			'arhk' => 'DH',
			'arhe' => '1',
			'arhs' => '1',
			'arhsk' => 'BbO',
		)
	),
	
	250 => array(
		'name' => '功德仙人',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '随喜赞叹放生功德',
		'effect' => '看，矿泉水它舍不得走，它要报恩！',
		'desc_skills' => '可以在清水池和风祭森林进行放生',
		'energy' => 100,
		'valid' => array(
			'skills'=>array(
				'551' => '0'
			)
		)
	),
	251 => array(
		'name' => '伐木挑战者',
		'rare' => 'A',
		'pack' => 'Way of Life',
		'desc' => '伐伐伐伐伐木工',
		'effect' => '要致富 先撸树',
		'desc_skills' => '获得技能「伐木」：只能发动一次，可将武器改造为伐木斧，使用该武器击杀敌人时获得额外金钱',
		'energy' => 100,
		'valid' => array(
			'skills'=>array(
				'552' => '0'
			)
		)
	),
	252 => array(
		'name' => '炸鸡勇者',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => 'V我50.jpg',
		'effect' => '星期四入场开局补给大幅强化，<br>但是，代价是什么呢？',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'553' => '0'
			)
		)
	),
	253 => array(
		'name' => '蟑螂',
		'rare' => 'B',
		'pack' => 'Best DOTO',
		'desc' => '一个命名了智力计量单位的人',
		'effect' => '他根本不需要视力',
		'desc_skills' => '获得最强大脑称号特性「脑力」，但视野只有两格',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'80' => '0',
				'554' => '0'
			)
		)
	),
	254 => array(
		'name' => '河童',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '通知：光学迷彩服严禁用于带薪拉屎',
		'effect' => '获得技能「迷彩」：发动后20秒内，你探索时不会遇到任何敌人和尸体',
		//'desc_skills' => '',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'555' => '0'
			)
		)
	),
	255 => array(
		'name' => '编乎用户',
		'rare' => 'B',
		'pack' => 'Cyber Zealots',
		'desc' => '分享你刚编的故事',
		'effect' => '开局获得20点技能点，但直到30级前都不能再获得技能点',
		//'desc_skills' => '',
		'energy' => 100,
		'valid' => array(
			'skillpoint' => '+20',
			'skills' => array(
				'537' => '0',
			),
		)
	),
	256 => array(
		'name' => '彩虹小马',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '逃杀就是魔法！',
		'effect' => '小马子，露出黑脚了吧',
		'desc_skills' => '马有四条腿，可以穿两双鞋子',
		'energy' => 100,
		'valid' => array(
			'skills'=>array(
				'557' => '0'
			)
		)
	),

	257 => array(
		'name' => '挑战挑战者者',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '“DOMO，挑战者=SAN，<br>挑战挑战者者 DESU.”',
		'effect' => '“挑战者，该战！”',
		'desc_skills' => '获得技能「挑战」：战斗中对卡片名称中含有「挑战者」的玩家伤害+30%，但自己受到的反噬伤害也会+30%',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'558' => '0',  
			),
		)
	),
	258 => array(
		'name' => '鬼叫王',
		'rare' => 'A',
		'pack' => '東埔寨Protoject',
		'desc' => '靠着独有的配音征服了地狱的鬼，<br>那一声声凄厉的鬼叫刻断了不知多少听众的DNA',
		'effect' => '获得战斗技「鬼叫」：放弃攻击并受到对方攻击，之后的游戏中对该角色的先制率上升15%',
		//'desc_skills' => '',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'560' => '0',
			),
		)
	),
	259 => array(
		'name' => '冻鳗婆罗门',
		'rare' => 'A',
		'pack' => 'Cyber Zealots',
		'desc' => '愤怒了，这个游戏的制作者根本不懂<br>什么才是真正的冻鳗梗！',
		'effect' => '我要好好教教你们真正的冻鳗姿势',
		'desc_skills' => '根据内定称号，获得额外的合成姿势',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'563' => '0',
			),
		)
	),	
	260 => array(
		'name' => '初动',
		'ruby' => 'FirstMove',
		'rare' => 'S',
		'pack' => 'Ranmen',
		'desc' => '西之国于2017年制造的最先进的AI，下井字棋的水平远远超过人类。<br><br><span class="evergreen b">『它在完全信息博弈中是不败的。<br>可惜的是现实并不是一个<br>完全信息博弈游戏。』</span>',
		'effect' => '称号固定为走路萌物，但每升5级可以从五个称号技能中选择一个学习（总共可学习10次，某些技能不可选）',
		'energy' => 100,
		'valid' => array(
			'club' => '17',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0',
				'564' => '0',
			),
		)
	),
	261 => array(
		'name' => '随机数眷顾者',
		'rare' => 'B',
		'pack' => 'Ranmen',
		'desc' => '每次抽签都有好结果的幸运儿<br><br><span class="evergreen b">『随机看起来最弱，<br>有时却是最强。』</span>',
		'effect' => '称号固定为走路萌物，但开局时会随机获得1个称号特性和6个技能',
		'energy' => 100,
		'valid' => array(
			'club' => '17',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0',
				'565' => '0',
			),
		)
	),
	262 => array(
		'name' => 'S.G.G.K',
		'rare' => 'A',
		'pack' => '東埔寨Protoject',
		'desc' => '“T O · M E · R U ! ! !”',
		'effect' => '获得技能「百战」且开局即解锁，<br>但闪避率-20%',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'566' => '0',
				'34' => '0',
			),
		)
	),
	263 => array(
		'name' => '自律人偶M',
		'rare' => 'A',
		'pack' => '東埔寨Protoject',
		'desc' => '看起来人畜无害的人偶妖怪，<br>要不摸一下试试',
		'effect' => '试试就逝世',
		'desc_skills' => '获得黑衣组织称号特性「淬毒」和「毒师」，以及技能<br>「毒雾」：自动给丢弃的无毒补给和敌人包裹内补给下毒',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'219' => '0',
				'220' => '0',
				'224' => '0',
				'567' => '0',
			),
		)
	),
	264 => array(
		'name' => '……',//我再想想怎么用梗
		'rare' => 'B',
		'pack' => 'hidden',
		'desc' => '……',
		'effect' => '开局装备能看到神秘数字的眼镜',
		'energy' => 100,
		'valid' => array(
			'arh' => '战斗力指示器',
			'arhk' => 'DH',
			'arhe' => '76',
			'arhs' => '54',
			'arhsk' => 'c',
			'skills' => array(
				'568' => '0',
			),
		)
	),
	265 => array(
		'name' => '未确认生命态',
		'rare' => 'A',
		'pack' => '東埔寨Protoject',
		'desc' => '不过是路过的小石头而已',
		'effect' => '开局携带可暂时隐身的药剂。如果<br>激活人数大于5，将所有其他玩家传送到墓地',
		'energy' => 100,
		'valid' => array(
			'itm6' => '「被厌恶者的哲学」',
			'itmk6' => 'MB',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '^mbid246',
			'skills' => array(
				'569' => '0',
			),
		)
	),
	266 => array(
		'name' => '鼠鼠',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '鼠鼠我啊，最喜欢钱了！',
		'effect' => '开局携带硬币和金色方块',
		'energy' => 0,
		'valid' => array(
			'wep' => '硬币',
			'wepk' => 'WC',
			'wepe' => '2',
			'weps' => '233',
			'wepsk' => '',
			'itm6' => '金色方块',
			'itmk6' => 'X',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	267 => array(
		'name' => '地震雷火事老爹',
		'title' => '老爹',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '会表演用眼部激光煎鸡蛋的顽固老爹',
		'effect' => '怒气上限为255',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'570' => '0',
			),
		)
	),
	268 => array(
		'name' => 'G战队·闪光黑',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '才不是蟑螂！',
		'effect' => '是萤火虫啦！',
		'desc_skills' => '开局为空手，但带有集气属性',
		'energy' => 0,
		'valid' => array(
			'wep' => '☆骑士飞踢☆',
			'wepk' => 'WN',
			'wepe' => '0',
			'weps' => '∞',
			'wepsk' => 'c',
		)
	),
	269 => array(
		'name' => '超次元巨大机器人',
		'rare' => 'S',
		'real_rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '河童出品的高109米重约2吨的巨型<br>机器人',
		'effect' => '它正是最强的无敌的魔神（大嘘）',
		'desc_skills' => '攻击力和防御力是正常的上万倍（仅限显示）',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'571' => '0',
			),
		)
	),
	270 => array(
		'name' => '大番薯',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '堆肥桶不是冰箱！',
		'effect' => '进场时地图上刷新额外的回复道具',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'572' => '0',
			),
		)
	),
	271 => array(
		'name' => '稀神老仙',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '哎呀奶不死的啊，这怎么奶死嘛，<br>地上人骑脸怎么输？',
		'effect' => '啊不好，说得太多作战就要失败了',
		'desc_skills' => '进场时祝福在场的其他玩家',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'577' => '0',
			),
		)
	),
	272 => array(
		'name' => '鼓哥',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '今天正局来视察我们乐团，给了我们好大的一个鼓啊！',
		'effect' => '（翻页）<br>……哦，还有一个舞！',
		'desc_skills' => '开局携带鼓棒和太鼓',
		'energy' => 120,
		'valid' => array(
			'wep' => '达人的太鼓棍棒',
			'wepk' => 'WP',
			'wepe' => '7',
			'weps' => '7',
			'wepsk' => 'N',
			'ara' => '达人的太鼓棍棒',
			'arak' => 'WP',
			'arae' => '6',
			'aras' => '6',
			'arask' => 'e',
			'arf' => '太鼓乱舞',
			'arfk' => 'WP',
			'arfe' => '5',
			'arfs' => '5',
			'arfsk' => 'c',
		)
	),
	273 => array(
		'name' => '尼特姬',
		'rare' => 'S',
		'pack' => '東埔寨Protoject',
		'desc' => '大家好啊，我是尼特姬<br>今天给大家来点不想看的难题啊',
		'effect' => '把兔子剥皮烤熟就是火鸟了哦！',
		'desc_skills' => '获得技能「难题」：战斗技，使对方玩家获得一个配方，该玩家未完成此配方时，探索无法遇到你且受到你攻击时所有技能无效；该玩家完成此配方时，自动将合成产物交给你。<br>15级时解锁，对每名玩家限一次',
		'energy' => 120,
		'valid' => array(
			'skills' => array(
				'575' => '0',
			),
		)
	),
	274 => array(
		'name' => '船长',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '宝船『亲民号』的船长，超亲民',
		'effect' => '東埔寨造舰一直不繁荣，为什么呢？',
		'desc_skills' => '开局装备带有冻气、冰华和冲击属性的钝器',
		'energy' => 100,
		'valid' => array(
			'wep' => '大建「沉船幽灵」',
			'wepk' => 'WP',
			'wepe' => '44',
			'weps' => '648',
			'wepsk' => 'ikN',
		)
	),
	275 => array(
		'name' => '饿灵',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '昔日吃不到八分饱的粉色恶魔，如今已是独当一面的路人甲了',
		'effect' => '可以把「增殖的G」、夜雀歌谱和<br>牛肉汤当做龙料理使用',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'574' => '0',
			),
		)
	),
	276 => array(
		'name' => '灵异巫女',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '东之国的某个小神社，有一名<br>拥有力量的巫女。<br>为了泄神社被破坏之愤，她带上御币和符札，誓将门中的魔物全部消灭',
		'effect' => '假  的<br><br>实际上这是一个打砖块的游戏',
		'desc_skills' => '开局获得御币和一个随机的方块',
		'energy' => 0,
		'valid' => array(
			'wep' => '御币',
			'wepk' => 'WP',
			'wepe' => '15',
			'weps' => '10',
			'wepsk' => '',
			'itm6' => array('红色方块', '白色方块', '黄色方块', '黑色方块', '绿色方块', 'X方块', 'Y方块'),
			'itmk6' => 'Z',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	277 => array(
		'name' => '月战老兵',
		'rare' => 'A',
		'pack' => '東埔寨Protoject',
		'desc' => '月兔不死，只会逐渐沦为大人物的<br>玩物',
		'effect' => '开局获得枪械和弹药，并获得黑衣组织技能「衰弱」',
		'desc_skills' => '「衰弱」：被你击中的敌人在10秒内无法处理伤口或异常状态',
		'energy' => 100,
		'valid' => array(
			'wep' => '☆粉红毛兔兔☆',
			'wepk' => 'WG',
			'wepe' => '77',
			'weps' => '1',
			'wepsk' => 'wy',
			'itm6' => '座药',
			'itmk6' => 'GBe',
			'itme6' => '1',
			'itms6' => '55',
			'itmsk6' => '',
			'skills' => array(
				'221' => '0', 
			),
		)
	),
	278 => array(
		'name' => '淡水人鱼',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '在过于强大的同族灭绝之后，<br>她成为了需要保护的水产品',
		'effect' => '开局位于墓地，并装备可能很稀有的投系武器',
		'energy' => 0,
		'valid' => array(
			'pls' => '9',
			'wep' => array('「珠泪哀歌族·梅洛人鱼」-仮', '「珠泪哀歌族·小美人鱼」-仮','「珠泪哀歌族·塞壬人鱼」-仮','「珠泪哀歌族·水仙女人鱼」-仮','「梦幻崩影·人鱼」-仮','浅水奇袭','浅水奇袭II','鱼弹突击'),
			'wepk' => 'WC',
			'wepe' => '33',
			'weps' => '33',
			'wepsk' => '',
		)
	),
	279 => array(
		'name' => '异色眼护伞',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '東埔寨人下雨根本不用伞，这让她为失去存在价值而愤怒',
		'effect' => '在被探索发现时，可能会显示为发现<br>一个道具',
		'desc_skills' => '要是拾取的话就会被吓一跳！',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'580' => '0', 
			),
		)
	),
	//已经填上了
	//老板，来个20个卡位的空白
	280 => array(
		'name' => 'Acg_xilin',
		'rare' => 'B',
		'pack' => 'Top Players',
		'desc' => 'ACFUN的创建者，是他在ACFUN主页上挂上了dts的链接，推动dts迈出了最重要的一步',
		'effect' => '这就是你们把猴子杀了几万遍的理由吗？',
		'desc_skills' => '开局携带游戏解除钥匙（一把锐器）',
		'energy' => 100,
		'valid' => array(
			'itm6' => '「游戏解除钥匙」',
			'itmk6' => 'WK',
			'itme6' => '160',
			'itms6' => '360',
			'itmsk6' => Array('g', 'l'),
		)
	),
	281 => array(
		'name' => 'Azazil',
		'rare' => 'B',
		'pack' => 'Top Players',
		'desc' => '简中环境下流传的各类php大逃杀的最初的编写者',
		'effect' => '就算在十几年过去的今天，游戏代码的一些角落里依然能找到他当初的痕迹',
		'desc_skills' => '开局携带■生 存 游 戏■源代码（一把拥有物抹+属抹的远程武器）',
		'energy' => 100,
		'valid' => array(
			'itm6' => '■生 存 游 戏■源代码',
			'itmk6' => 'WG',
			'itme6' => '7',
			'itms6' => '7',
			'itmsk6' => 'oBbS',
     
		)
	),
	282 => array(
		'name' => '银色盒子的触手',
		'rare' => 'S',
		'pack' => 'Standard Pack',
		'desc' => '<span class="white b">『这就是所谓幻境的最终目标？<br>不，这只是一个开始。』</span>',
		'effect' => '开局可以选择任意一个称号',
		//'desc_skills' => '',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'275' => '0',
			),
		)
	),
	283 => array(
		'name' => '随便',
		'rare' => 'C',
		'pack' => 'Ranmen',
		'desc' => '随便',
		'effect' => '随便',
		'desc_skills' => '开局随便选一个称号',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'276' => '0',
			),
		)
	),
	284 => array(
		'name' => '黄泉重女',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '就算在变态层出不穷的東埔寨，<br>她也是变态跟踪狂里最变态的那一个',
		'effect' => '获得战斗技「追猎」：战斗中可以标记玩家，之后能持续获知该玩家的位置',
		'desc_skills' => '10级时解锁。同时只能标记一名玩家',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'556' => '0',
			),
		)
	),
	285 => array(
		'name' => '数字生命',
		'rare' => 'B',
		'pack' => 'Cyber Zealots',
		'desc' => '这个月幻境的访问量多了26倍，你有什么头猪吗？',
		'effect' => '连斗前不会显示在幸存名单中',
		//'desc_skills' => '',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'536' => '0',
			),
		)
	),
	286 => array(
		'name' => '小绵羊',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '饕餮为什么不上？',
		'effect' => '今天，我手震；今天，我心疼。…为什么会这样？我付出一切，却得不到想要的一点爱。…蓝…蓝…我…我…<br>我是真的好很爱你的，为何你要这样对我呀？！<br>呜—哇——呱——咩——',
		'desc_skills' => '获得亡灵骑士技能「黑暗」，但是开局体力上限-300
		<br>「黑暗」：攻击敌人时有一定概率吸取对方1点全系熟练度和1点生命上限',
		'energy' => 100,
		'valid' => array(
			'sp' => '-300',
			'msp' => '-300',
			'skills' => array(
				'61' => '0',
			),
		)
	),
	287 => array(
		'name' => '老板娘 有江露美',
		'title' => '老板娘',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '有着暴躁的性格与谩骂顾客的口癖。对于一直不付钱的顾客会毫不留情地进行制裁。
	<br>但这却获得了某方面人士们的好评，现在已经不再有会付钱的顾客',
		'effect' => '开局携带随机一种饮料',
		'energy' => 0,
		'valid' => array(
			'itm6' => Array('伏特加','一杯八分满的啤酒','咖啡酒','百利甜','柠檬汁','红石榴汁','牛肉汤','バカ⑨制冰块'),
			'itmk6' => 'HB',
			'itme6' => '100',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	288 => array(
		'name' => '西瓜',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '伊吹县的名物',
		'effect' => '开局携带西瓜',
		'energy' => 0,
		'valid' => array(
			'itm6' => '西瓜',
			'itmk6' => 'HB',
			'itme6' => '60',
			'itms6' => '5',
			'itmsk6' => '',
		)
	),
	289 => array(
		'name' => '拷贝猫',
		'rare' => 'B',
		'pack' => 'Ranmen',
		'desc' => '这张图猫鼠队铁定跑不出来',
		'effect' => '结算！',
		'desc_skills' => '开局能随机复制并获取场上存活玩家的一项技能（包括称号技能）',
		'energy' => 100,
		'valid' => array(
		  'skills' => array(
				'529' => '3', 
			),
		)
	),
	290 => array(
		'name' => '狱火鸡',
		'rare' => 'S',
		'pack' => '東埔寨Protoject',
		'desc' => '不老不死。<br>没有比这个更不看前提条件、更强大的能力了吧',
		'effect' => '数值……属性……称号……技能……<br>在不死的存在看来，一切都只是<br>游戏的规则而已',
		'desc_skills' => '被杀死1分钟后你将复活。每次复活后，这个时间都将翻倍。在你死亡期间，其他玩家可以正常拾取你尸体上的道具，也会正常获得幸存等胜利',
		'energy' => 100,
		'valid' => array(
		  'skills' => array(
				'539' => '0', 
			),
		)
	),
	291 => array(
		'name' => '根流',//TODO
		'ruby' => 'root_stream',
		'rare' => 'S',
		'pack' => 'Cyber Zealots',
		'desc' => '『肆起』的人工智能助手，『硅邦』的数字经济战略形象大使，也是『伊甸』的竞技场的电子播报员。为了方便它的主人朝令夕改，根流被赋予了极高的权限，可以轻易调动硅邦的一切资源，所幸它只是一个没法通过图灵测试的国产人工智能而已',
		'effect' => '获得技能「提权」：你可以消耗5个升级点，更换可选的称号列表。此外，每个称号仅限一次，你可以在称号列表中任意切换称号，但切换后不保留原称号技能的数值',
		'energy' => 100,
		'valid' => array(
		  'skills' => array(
				'pls' => '0', //TODO
			),
		)
	),
	292 => array(
		'name' => '邪教徒',
		'rare' => 'A',
		'pack' => 'Top Players',
		'desc' => '他除错的力量无人能及',
		'effect' => '获得除错模式特殊技能「除错」，每层都会让物理固定伤害增加3点，但20级之前不会获得金钱和技能点奖励',
		'desc_skills' => '「除错」：提交指定的物品来追查病毒、修复幻境系统，除错成功会获得奖励。在某些等级会获得与正常除错模式不同的奖励',
		'energy' => 100,
		'valid' => array(
		  'skills' => array(
				'424' => '0',
				'538' => '0',
			),
		)
	),
	293 => array(
		'name' => '门番',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => 'zzzzzZZZZZ',
		'effect' => '这个门番在睡觉的时候大声喊出Z来假装自己在探索',
		'desc_skills' => '在睡眠、治疗、静养时视为强袭姿态',
		'energy' => 0,
		'valid' => array(
		  'skills' => array(
				'579' => '0',
			),
		)
	),
	294 => array(
		'name' => 'Martin_Chloride',
		'title' => 'Martin',
		'rare' => 'B',
		'pack' => 'Top Players',
		'desc' => '竞品游戏《东方大逃杀》的编写者。虽然游戏已经很久没更新了，但其代码给了本游戏不少启发',
		'effect' => '开局携带一份特色合成配方：触手的力量 + 名字包含「蘑菇」的道具 + 名字包含「魔导书」的道具 = 码符「终极BUG·拉电闸」/灵力兵器/1000/6/连击',
		//'desc_skills' => '',
		'energy' => 100,
		'valid' => array(
		  'itm6' => '《东方大逃杀》源代码',
			'itmk6' => 'R',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '52',
		)
	),
	295 => array(
		'name' => '搞事铃',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '租书店的妖怪，种族是防撞桶，拥有作死程度的能力',
		'effect' => '获得亡灵骑士技能「腐蚀」。开局携带妖魔书',
		'desc_skills' => '「腐蚀」：每次发动提升50点生命上限，但降低自己的战斗伤害7%，每局最多使用7次',
		'energy' => 100,
		'valid' => array(
		  'skills' => array(
				'62' => '0',
			),
			'itm6' => '妖魔书',
			'itmk6' => 'VF',
			'itme6' => '50',
			'itms6' => '1',
			'itmsk6' => '',
		)
	),
	296 => array(
		'name' => '地狱三头犬',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '新作获得意外的高人气的<br>白毛红瞳兽娘',
		'effect' => '开局携带两个捕兽夹',
		'energy' => 0,
		'valid' => array(
	  	'wep' => '捕兽夹',
			'wepk' => 'WPC',
			'wepe' => '240',
			'weps' => '2',
			'wepsk' => '',
			'ara' => '捕兽夹',
			'arak' => 'WCP',
			'arae' => '240',
			'aras' => '2',
			'arask' => '',
		)
	),
	297 => array(
		'name' => '毛玉',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '能在天上飞行的神秘毛球，<br>是杂鱼中的杂鱼',
		'effect' => '开局携带P点和蓝点',
		'energy' => 0,
		'valid' => array(
			'itm5' => '[Ｐ]',
			'itmk5' => 'MA',
			'itme5' => '1',
			'itms5' => '50',
			'itmsk5' => '',
			'itm6' => '[点]',
			'itmk6' => 'MS',
			'itme6' => '1',
			'itms6' => '50',
			'itmsk6' => '',
		)
	),
	298 => array(
		'name' => '妖精队长',
		'rare' => 'A',
		'pack' => '東埔寨Protoject',
		'desc' => '有这么一位角色，ta是某部漫画的主角，衣服的图案是星条旗，原本很弱小但经改造获得了强大的力量，投掷圆形的武器，在战争中占领过敌方的首都，还登陆过月球。请问ta是……',
		'effect' => '开局携带能用来砸人的巨大月亮',
		'energy' => 100,
		'valid' => array(
			'wep' => '「狂気の月」',
			'wepk' => 'WC',
			'wepe' => '1970',
			'weps' => '13',
			'wepsk' => 'N',
		)
	),
	
	299 => array(
		'name' => '⑨',
		'rare' => 'A',
		'real_rare' => 'C',//真实的爆率
		'print_rare_mark' => '⑨',//只在显示罕贵字母时用的
		'pack' => '東埔寨Protoject',
		'desc' => '最强！',
		'effect' => '获得一个冰雪聪明的头像',
		//'desc_skills' => '',
		'energy' => 9,
		'valid' => array(
			'icon' => 'n_999.gif',
			'sNo' => 9,
		)
	),
	
	300 => array(
		'name' => '占位符，搞点大的',
		'rare' => 'C',
		'pack' => 'hidden',
		'desc' => '占位符',
		'effect' => '占位符占位符',
		'desc_skills' => '占位符占位符占位符',
		'energy' => 0,
		'valid' => array(
			'pls' => 0,
		)
	),
	//这里有40张空位，是一个卡组
	301 => array(
		'name' => '乌冬馄饨',
		'ruby' => 'サークル　うどんワンタン',
		'rare' => 'B',
		'pack' => 'Top Players',
		'desc' => '画技相当高超的画师，<br>为dts贡献了核爆结局的动图',
		'effect' => '开局获得一个奇怪的按钮',
		'desc_skills' => '这个按钮显然是不能按的',
		'energy' => 120,
		'valid' => array(
			'itm6' => '奇怪的按钮',
			'itmk6' => 'HB',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '^res_<:comp_itmsk:>{《分镜绘制指南》,VD,30,2,,}1^reptype1',
		)
	),
	302 => array(
		'name' => '猫耳吸血鬼',
		'ruby' => 'noui',
		'rare' => 'B',
		'pack' => 'Top Players',
		'desc' => 'dts开头和结尾剧情插图的绘制者',
		'effect' => '开局获得红色和蓝色的两把钥匙',
		'desc_skills' => '红钥匙和蓝钥匙分别是拥有火焰+碎甲和冻气+碎刃属性的投掷武器',
		'energy' => 100,
		'valid' => array(
			'wep' => '对不起做不到的蓝钥匙',
			'wepk' => 'WC',
			'wepe' => '100',
			'weps' => '15',
			'wepsk' => 'i^wc1',
			'itm6' => '打不过就加入的红钥匙',
			'itmk6' => 'WC',
			'itme6' => '100',
			'itms6' => '15',
			'itmsk6' => 'u^ac1',
		)
	),
	303 => array(
		'name' => '第一地狱·该隐环',
		'ruby' => 'Normal',
		'title' => '第一地狱',
		'rare' => 'B',
		'pack' => 'hidden',
		'desc' => '<span class="vermilion b">『「NPC底座」可以量产精英战士，少数幸运儿甚至能成为英雄、怪物，乃至神明……』</span>',
		'effect' => '<span class="vermilion b">『然而，这只是幻境的馈赠，<br>不是你自己的力量。』</span>',
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
			'skillpoint' => '+5',
		)
	),
	304 => array(
		'name' => '第二地狱·安特诺尔环',
		'ruby' => 'Hard',
		'title' => '第二地狱',
		'rare' => 'A',
		'pack' => 'hidden',
		'desc' => '<span class="vermilion b">『熟练激发自己的潜能，而无需借助外界的力量。如果把这个能力<br>带回现实，就足够独当一面了。』</span>',
		'effect' => '<span class="vermilion b">『但这也只是刚刚开始。』</span>',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'275' => '0',
			),
		)
	),
	305 => array(
		'name' => '第三地狱·托勒密环',
		'ruby' => 'Lunatic',
		'title' => '第三地狱',
		'rare' => 'S',
		'pack' => 'hidden',
		'desc' => '<span class="ltcrimson b">『脆弱、虚弱、笨拙、迷离，<br>而敌人却远比以前强大。<br><br>根本不公平……<br>你一定想这么说吧。』</span>',
		'effect' => '<span class="ltcrimson b">『毕竟，这个世界<br>从一开始就没有公平过。』</span>',
		'energy' => 0,
		'valid' => array(
		  'club' => '17',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0',
			),
		)
	),
	306 => array(
		'name' => '最终地狱·犹大环',
		'ruby' => '3倍☆ICE CREEEEAM!!!!!!',
		'title' => '最终地狱',
		'rare' => 'M',
		'pack' => 'hidden',
		'desc' => '<span class="white b">既然你都来到这里了</span>',
		'effect' => '<span class="white b">应该做好心理准备了吧？</span>',
		'energy' => 0,
		'valid' => array(
		  'club' => '17',
			'skills' => array(//todo
				'10' => '0', 
				'11' => '0', 
				'12' => '0',
			),
		)
	),
	307 => array(
		'name' => '剑怔人',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '该用户已被封禁',
		'effect' => '开局位于冰封墓场',
		'energy' => 0,
		'valid' => array(
			'pls' => 26,
		)
	),
	308 => array(
		'name' => '程序员老公',
		'rare' => 'C',
		'pack' => 'Event Bonus',
		'desc' => '你下班后买五个苹果，<br>如果看到红薯，买一个。',
		'effect' => '你怎么就买了一个苹果？',
		'desc_skills' => '开局携带一个苹果，视野里能看到红薯',
		'energy' => 0,
		'valid' => array(
			'itm5' => '苹果',
			'itmk5' => 'HB',
			'itme5' => '90',
			'itms5' => '1',
			'itmsk5' => '',
			'skills' => array(
				'543' => '0',
			),
		),
		'ignore_global_ach' => 1,//不参与终生成就判定
	),
	309 => array(
		'name' => '菌菌子',
		'rare' => 'A',
		'pack' => 'Top Players',
		'desc' => '很早就接触大逃杀的画师，<br>画了可可爱爱的表情包。<br><br>曾经的画画爱好者<br> 如今已是画触！',
		'effect' => '获得高速成长特性「高速」和技能<br>「神功」，并且开局装备表情包。',
		'desc_skills' => '「高速」：每次攻击有2/3概率额外获得1点熟练度，2/3概率额外获得1点经验值<br>「神功」：战斗时获得的熟练度+1',
		'energy' => 100,
		'valid' => array(
			'arh' => '表情包',
			'arhk' => 'DH',
			'arhe' => '233',
			'arhs' => '233',
			'arhsk' => Array('u', 'i', 'e', 'w', 'p', 'z'),
			'skills' => array(
				'225' => '0', 
				'229' => '0',
			),
		),
	),
	310 => array(
		'name' => '姬械匠神',
		'rare' => 'A',
		'pack' => '東埔寨Protoject',
		'desc' => '会教你唱山歌的广西烧烤摊大妈',
		'effect' => '开局称号为偶像歌姬，但技能为偶像歌姬特性「歌姬」、技能「安魂」「夺目」「回响」和妙手天成特性「妙手」、技能「精工」「沉心」「铸血」',
		'desc_skills' => '「歌姬」：初始和升级获得歌魂增加；初始习得歌曲《Alicemagic》和《Crow Song》
		<br>「安魂」：战斗技，本次攻击属性伤害+30%并会根据唱歌带来的临时增益数量而继续强化
		<br>「夺目」：在同一地点唱歌而消耗的歌魂累计达到240点时，该地点的角色对你的先制率会下降
		<br>「回响」：根据你歌魂与歌魂上限的比例关系获得「音波」属性或者「激奏2」属性
		<br>「妙手」：每次合成时获得2-4点经验值，升级后每次合成能获得额外的经验值和熟练度
		<br>「精工」：合成武器或防具时可能获得额外的效果值、耐久值与属性，可升级让概率和效果增加
		<br>「沉心」：消耗所有怒气值使下一次合成产物的效果值与耐久值增加
		<br>「铸血」：消耗50点生命上限，使下一次合成产物获得至多3个随机额外属性，且产物类别可能发生变化',
		'energy' => 100,
		'valid' => array(
		  'club' => '25',
			'skills' => array(
				'10' => '0', 
				'11' => '0', 
				'12' => '0',
				'87' => '0',
				'90' => '0',
				'91' => '0',
				'93' => '0',
				'97' => '0',
				'99' => '0',
				'100' => '0',
				'101' => '0',
				'1003' => array('learnedsongs' => '1_2'),
			),
		)
	),
	311 => array(
		'name' => '谷泽龙二',
		'rare' => 'B',
		'pack' => 'Standard Pack',
		'desc' => '复活赛有人教他基础了',
		'effect' => '只要你入场，所有玩家正常选择称号时只能选到一般称号',
		'desc_skills' => '部分卡片额外增加的称号选项不受影响',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'544' => '0'
			)
		)
	),
	341 => array(
		'name' => '麻薯',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '连一刻也没有为主子的晚餐哀悼，<br>立刻赶到战场的是',
		'effect' => '怎么只来了半个人？',
		'desc_skills' => '在幸存名单中显示为0.5人，开局携带半个灵力武器材料',
		'energy' => 100,
		'valid' => array(
			'itm6' => '★瓦衣山彐★',
			'itmk6' => 'X',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => 'z',
			'skills' => array(
				'583' => '0'
			)
		)
	),
	342 => array(
		'name' => '开门大吉猫咪',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '这里是肯東基，没有1+1，<br>也没有双吉',
		'effect' => '获得技能「招福」：可选择探索时获得金钱但角色发现率降低；或失去金钱但角色发现率增加',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'582' => '0'
			)
		)
	),
	343 => array(
		'name' => '妖精女仆',
		'rare' => 'M',
		'pack' => '東埔寨Protoject',
		'desc' => '主要负责增加女仆长的工作量',
		'effect' => '不获得技能「疾风」「人杰」<br>「整备」「谨慎」「团结」',
		'desc_skills' => '获得技能「摸鱼」：对NPC造成的战斗伤害降低',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'494' => '0'
			)
		)
	),
	344 => array(
		'name' => '油库里',
		'rare' => 'M',
		'pack' => '東埔寨Protoject',
		'desc' => '一种形似馒头的生物，品种繁多',
		'effect' => '<span style="font-size:9pt;font-family:Saitamaar,\'MS PGothic\',IPAMonaPGothic;">
<br>＿人人人人人人人人人人人人人人人＿
<br>＞　　　ゆっくりしていってね！！！　　　＜
<br>￣^Ｙ^Ｙ^Ｙ^Ｙ^Ｙ^Ｙ^Ｙ^Ｙ^Ｙ^Ｙ^Ｙ^Ｙ^Ｙ^Ｙ^￣
</span>',
		'desc_skills' => '随机发动一张「東埔寨Protoject」卡，但称号固定为走路萌物，且只有技能「治愈」',
		'energy' => 0,
		'valid' => array(
			//技能和称号的处理在skill584模块里
			'cardchange' => Array(
				'S_odds' => 20,
				'A_odds' => 20,
				'B_odds' => 30,
				'C_odds' => 30,
				'packlimit' => '東埔寨Protoject',
				'allow_EB' => false,
				'forced' => Array(117, 153, 157, 158, 186),//无视概率强制加入选择的卡（星莲船，冴冴，诹访子，灵梦，超魔理沙）
				'ignore_cards' => Array(241)//机制上必定选不到自己，这里可以放其他不想被选到的卡
			),
		)
	),
	345 => array(
		'name' => '复读机',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '「yahoo——」<br>「yahoo——」<br><br>「我可爱吗？——」<br>「我可爱吗？——」',
		'effect' => '「请关注博丽神社谢谢喵——」<br>「yahoo——」',
		'desc_skills' => '当前地图存在其他复读机时，攻击时视为具有音波属性',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'585' => '0'
			)
		)
	),
	346 => array(
		'name' => '叛逆天人',
		'title' => '逆天',
		'rare' => 'A',
		'pack' => '東埔寨Protoject',
		'desc' => '她不具备天人应有的品行，叛逆的性格也与其他天人不同',
		'effect' => '大家都把她称做',
		'desc_skills' => '获得一次性技能「天变」「地异」<br>「天变」：随机改变天气，12级时解锁<br>「地异」：将地图顺序上下翻转，直到下一次禁区，12级时解锁',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'588' => '0',
				'589' => '0',
			),
		)
	),
	347 => array(
		'name' => '仓鼠挑战者',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '为什么大家都不抽新卡，就因为没更新新卡吗？',
		'effect' => '持有切糕越多，受到的战斗伤害越低（至多-20%）',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'590' => '0',
			),
		)
	),
	348 => array(
		'name' => '太阳之子',
		'rare' => 'S',
		'pack' => '東埔寨Protoject',
		'desc' => '架着神灵，身披大氅，<br>消灭一切害人狼！',
		'effect' => '太阳的核子——就是我！<br>我的威力——就是强！',
		'desc_skills' => '获得技能「熔毁」：战斗时有33%、66%、1%概率<br>提高物理伤害6%、66%、666%，6级时解锁',
		'energy' => 120,
		'valid' => array(
			'skills' => array(
				'591' => '0',
			),
		)
	),
	349 => array(
		'name' => '绿眼葛笼',
		'ruby' => 'Green-Eyes Jealous Monster',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '凭什么那张卡值8700万？',
		'effect' => '我好嫉妒啊！',
		'desc_skills' => '获得技能「嫉妒」：攻击时使对手获得临时的属性弱化和同等<br>时长的临时技能「嫉妒」，时长取决于对手卡片的稀有度',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'592' => '0',
			),
		)
	),
	350 => array(
		'name' => '八意制药™',
		'rare' => 'S',
		'pack' => '東埔寨Protoject',
		'desc' => '東埔寨著名的座药制造企业',
		'effect' => '可以制造三无药品',
		'desc_skills' => '获得技能「秘药」：每60秒可以获得1个随机药物，<br>距上一次发动时间越长越容易得到强力的药物',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'593' => '0',
			),
		)
	),
	351 => array(
		'name' => 'M18 “地狱猫”',
		'title' => '地狱猫',
		'rare' => 'A',
		'pack' => '東埔寨Protoject',
		'desc' => '运送尸体的打火车，其敞篷设计在乘员中的差评率至今为零',
		'effect' => '获得可以把尸体打包带走的技能',
		'desc_skills' => '获得技能「猫车」：移动后将当前地点尽可能多的尸体加入视野，<br>可以把尸体上的全部道具和金钱一并带走。10级时解锁',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'597' => '20',
			),
		)
	),
	352 => array(
		'name' => '上老师',
		'rare' => 'A',
		'pack' => '東埔寨Protoject',
		'desc' => '寺子屋老师<br>和同姓职人没有任何关系',
		'effect' => '言传水平一般，但是身教很有力',
		'desc_skills' => '获得战斗技「劝学」：物理伤害+20%，使对手概率获得以下选项中的若干种：<br>晕眩2秒/头部受伤/经验值增加/全系熟练度增加。10级时解锁',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'595' => '0',
			),
		)
	),
	353 => array(
		'name' => '自媒体',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '靠搜索引擎写报道的家里蹲小报记者',
		'effect' => '可以用手机捏造新闻',
		'desc_skills' => '开局携带手机并获得技能「念写」：可以使用手机，获得随机的<br>投系武器、强化药物或合成材料',
		'energy' => 0,
		'valid' => array(
			'itm6' => '手机',
			'itmk6' => 'X',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '',
			'skills' => array(
				'596' => '0',
			),
		)
	),
	354 => array(
		'name' => '小桶',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '和钓瓶妖怪一起<del>攻克难关</del>提桶跑路',
		'effect' => '开局装备诅咒属性的腿部防具',
		'energy' => 0,
		'valid' => array(
			'arf' => '木桶',
			'arfk' => 'DF',
			'arfe' => '50',
			'arfs' => '30',
			'arfsk' => 'O',
		)
	),
	355 => array(
		'name' => '勇☆仪☆王',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '她有40张符卡',
		'effect' => '可以把游戏王武器当无属性的灵系武器使用',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'598' => '0',
			),
		)
	),
	356 => array(
		'name' => '蚀梦貘',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '适度酗酒益脑，沉迷睡觉伤身',
		'effect' => '获得一次性技能「迷梦」：使当前地图的玩家和生命值低于你的NPC变为治疗姿态和重视防御，并进入睡眠',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'599' => '0',
			),
		)
	),
	357 => array(
		'name' => '「周六夜狂热」',
		'rare' => 'A',
		'pack' => '東埔寨Protoject',
		'desc' => '身为龙宫使，负责传递来自龙神的<br>姿势',
		'effect' => '现在《太平要术》和《占星术导论》都是幻境必修的舞蹈教材',
		'desc_skills' => '获得技能「雷击」「预感」
		<br>「雷击」：你埋设的陷阱对敌人造成的伤害+15%
		<br>「预感」：你受到的陷阱伤害随机减少1~30%',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'249' => '0',
				'250' => '0',
			),
		)
	),
	358 => array(
		'name' => '烧烤与夜雀与夜色森林',
		'title' => '烤夜雀',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '面对大型连锁餐厅，正在为自己的生意和鸟身安全担忧的烧烤摊老板娘',
		'effect' => '有摇滚和烤串的绝活',
		'desc_skills' => '开局获得带有变奏和激奏1属性的烤肉工具和补给',
		'energy' => 100,
		'valid' => array(
			'ara' => '☆烤鳗鱼☆',
			'arak' => 'HB',
			'arae' => '300',
			'aras' => '10',
			'arask' => '^sa1',
			'art' => '烤肉组合',
			'artk' => 'A',
			'arte' => '1',
			'arts' => '1',
			'artsk' => '^sv1',
		)
	),
	359 => array(
		'name' => '加班挑战者',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '干到太晚然后在工位上睡着了',
		'effect' => '我这是在哪？',
		'desc_skills' => '开局处于睡眠状态，有30%概率获得额外的起始金钱',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'562' => '0',
			),
		)
	),
	//空一下360
	361 => array(
		'name' => '纵火狂',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '喜欢放火的熊孩子',
		'effect' => '开局携带使用后获得已升级的临时技能「聚能」的药物',
		'energy' => 100,
		'valid' => array(
			'itm6' => '炎符「火烧命莲寺」',
			'itmk6' => 'MB',
			'itme6' => '100',
			'itms6' => '1',
			'itmsk6' => '^mbid26^mblvl2^mbtime480',
		)
	),
	362 => array(
		'name' => '阿加莎克里斯Q',
		'title' => '阿Q',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '人类村落的著名小说家，有着过目不忘的能力',
		'effect' => '她意思之间，似乎觉得人生天地间，大约本来有时也未免要早夭的',
		'desc_skills' => '探索记忆数目为200格，但升级到30级后会死亡',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'701' => '0',
			),
		)
	),
	363 => array(
		'name' => '邪神·神之化身',
		'title' => '⑩',
		'rare' => 'M',
		'pack' => '東埔寨Protoject',
		'desc' => '光天化日之下潜藏于黑暗中的魔物',
		'effect' => '你的身躯永远藏于黑暗之中<br>……是这样吗？',
		'desc_skills' => '获得技能「宵暗」：你发现其他角色和其他角色发现你时都显示为？？？',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'702' => '0',
			),
		)
	),
	364 => array(
		'name' => '月光蝶',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '東埔寨有句老话，叫做闷声发大财',
		'effect' => '你战斗时不会发出任何声音',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'703' => '0',
			),
		)
	),
	365 => array(
		'name' => '大鹌鹑',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '曾经登场于电波春节活动的大型野生动物（？）',
		'effect' => '在攻击时可能会发出特殊的台词',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'704' => '0',
			),
		)
	),
	366 => array(
		'name' => '恶魔导书童',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '魔导图书馆非法雇佣的童工',
		'effect' => '开局携带一本随机魔导书',
		'energy' => 0,
		'valid' => array(
			'itm6' => array('☆魔导书整理☆','☆残页的魔导书☆','☆冰火之魔导书☆','☆创造之魔导书☆','☆奥义之魔导书☆','☆蜡板之魔导书☆','☆水卜之魔导书☆','☆恶灵之魔导书☆','☆律法之魔导书☆','☆死灵之魔导书☆','☆隐藏的魔导书☆'),
			'itmk6' => array('VF','VF','VF','VF','VF','VF','VF','VS'),
			'itme6' => '30',
			'itms6' => '1',
			'itmsk6' => '236',
		)
	),
	367 => array(
		'name' => '六〇年的東埔寨裁判',
		'title' => '東埔寨裁判',
		'rare' => 'A',
		'pack' => '東埔寨Protoject',
		'desc' => '“法律是■■■■所定下的约束。<br>法无法制裁的罪过就由我来制裁。”',
		'effect' => '你有点太极端了',
		'desc_skills' => '对使用「東埔寨Protoject」卡的角色造成超过80%当前生命值的伤害时，自动将其秒杀',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'541' => '0',
			),
		)
	),
	368 => array(
		'name' => '重构码语者',
		'rare' => 'A',	
		'pack' => 'Event Bonus',
		'desc' => '很多愣头青觉得自己是天才，<br>可以把屎山重构了。<br>他们中的大部分人引发了屎崩，<br>永远埋在了几千米高的屎山之下。<br><br>极少数人活了下来。<br>他们真的做到了，<br>他们真的重构了整座屎山！',
		'bigdesc' => 1,
		'effect' => '重构之后的那个东西，被后人称为——<br>屎山2.0。',
		'desc_skills' => '踩雷率大幅提高，在受到陷阱伤害但幸存后获得<br>一个1.2倍效果值的同名陷阱',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'707' => '0',
			),
		),
		'ignore_global_ach' => 1,//不参与终生成就判定
	),
	369 => array(
		'name' => '扎不多先生',
		'rare' => 'S',	
		'pack' => 'Event Bonus',
		'desc' => '扎不多得了😅',
		'effect' => '大字比夭字只少一小撇，<br>不是差不多吗？',
		'desc_skills' => '通常合成时，你可以将一个素材当做仅相差一个字符的素材使用<br>同一次合成中仅限一个素材，开局15分钟后或15级时解锁',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'708' => '0',
			),
		),
		'ignore_global_ach' => 1,//不参与终生成就判定
	),
	370 => array(
		'name' => '魔术狸猫',
		'rare' => 'A',
		'pack' => '東埔寨Protoject',
		'desc' => '本地的妖怪实在是太没有狸猫了！',
		'effect' => '获得战斗技「调换」：命中后把对方的武器打落到视野里，并混入两个假道具中。只能对玩家使用',
		'desc_skills' => '6级时解锁',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'709' => '0',
			),
		)
	),
	371 => array(
		'name' => '针剑士',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '你变大吧！',
		'effect' => '获得技能「宝槌」：将全部视野置入记忆，并将等同于视野上限个数的当前地点道具加入临时视野，冷却时间30秒',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'712' => '0',
			),
		)
	),
	372 => array(
		'name' => '山里灵活の狗',
		'title' => 'の',
		'rare' => 'A',
		'pack' => '東埔寨Protoject',
		'desc' => '大将叫我来巡山，我把弹幕转一转~',
		'effect' => '这瀑布的水，无比的甜，<br>秋天充满节奏感~',
		'desc_skills' => '获得技能「一览」「千里」：
			<br>「一览」：每当你探索发现物品都能额外发现3个道具
			<br>「千里」：你额外获得3格视野',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'713' => '3',
				'542' => '3',
			),
		)
	),
	373 => array(
		'name' => '转转',
		'rare' => 'C',
		'pack' => '東埔寨Protoject',
		'desc' => '不转不是東埔寨人',
		'effect' => '开局携带一堆雏人形',
		'energy' => 100,
		'valid' => array(
			'wep' => '雏人形',
			'wepk' => 'WF',
			'wepe' => '50',
			'weps' => '20',
			'wepsk' => '',
			'itm5' => '雏人形',
			'itmk5' => 'TN',
			'itme5' => '50',
			'itms5' => '5',
			'itmsk5' => 'O',
			'itm6' => '雏人形',
			'itmk6' => 'X',
			'itme6' => '1',
			'itms6' => '3',
			'itmsk6' => '',
		)
	),
	374 => array(
		'name' => '春化精',
		'rare' => 'B',
		'pack' => '東埔寨Protoject',
		'desc' => '发春了哦！',
		'effect' => '进场后直到下一次禁区，所有角色视为随机拥有热恋或同志属性',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'714' => '0',
			),
		)
	),
	375 => array(
		'name' => '大摩法师',
		'rare' => 'S',
		'pack' => '東埔寨Protoject',
		'desc' => '如果施主听不懂大乘佛法，<br>贫尼也略懂一些摩托车维修艺术',
		'effect' => '开局时被封印<br>解除后，获得技能「灵力」「圣光」「金刚」和「神速」，并在之后第一次探索时获得强力的腿部外甲防具',
		'desc_skills' => '「封印」：无法行动，达到10分钟或受到其他人的攻击时解除
			<br>「灵力」：敌人攻击你时其命中率降低，你使用灵系武器的体力消耗降低，可以升级
			<br>「圣光」：你造成的属性伤害+15%，如果敌人已有对应异常状态，伤害额外+50%
			<br>「金刚」：每100点基础防御降低所受物理固定伤害或爆炸伤害1点，可以切换，可以升级
			<br>「神速」：战斗中基础攻击力+10%，每次攻击有50%概率让基础攻击力+1，有额外65%的概率反击',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'715' => '0',
			),
		)
	),
	376 => array(
		'name' => '圣殿的紫色隐士',
		'rare' => 'S',
		'pack' => '東埔寨Protoject',
		'desc' => '站在你面前的是……<br>刚满十七岁的贤者哟☆',
		'effect' => '近年来東埔寨高发的异世界转生事件的幕后黑手',
		'desc_skills' => '获得技能「神隐」「废线」<br>「神隐」：可以将道具或生命值低于自己的NPC送入异次元<br>「废线」：战斗技，可以使用异次元中的道具和NPC攻击对手',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'586' => '30',
				'587' => '0',
			),
		)
	),
	377 => array(
		'name' => '随机数挑战者',
		'rare' => 'C',
		'pack' => 'Ranmen',
		'desc' => '如果有什么操作着这个世界的随机，<br>那么这就是我的反叛！',
		'effect' => '你可以随时抛硬币和丢骰子',
		'desc_skills' => '但是没有什么额外的作用',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'716' => '0',
			),
		)
	),
	378 => array(
		'name' => '随机数大神叛军',
		'rare' => 'M',
		'pack' => 'Ranmen',
		'desc' => '闭眼选一个',
		'effect' => '你移动时会随机选择一个目标地点；获得物品时会变为获得一个随机商店或地图道具；遭遇NPC时会变为遭遇随机地点的一个NPC',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'717' => '0',
			),
		)
	),
	379 => array(
		'name' => '硬币（？）',
		'rare' => 'M',
		'pack' => 'Ranmen',
		'desc' => '你是一枚硬币。<br>随机数大神的信徒们沐浴更衣，然后虔诚地将你高高抛起，以此祈求神迹',
		'effect' => '你入场时有一半概率脸朝下',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'718' => '0',
			),
		)
	),
	380 => array(
		'name' => '『最后密传』',
		'ruby' => 'Last Resort',
		'title' => '最后密传',
		'rare' => 'A',
		'pack' => 'Event Bonus',
		'desc' => '<span class="ltcrimson b">『没有任何预警，没有任何情报，<br>没有任何资源。<br>比任何预案都要更极端、更绝望。<br><br>也许红杀已经毁灭了。<br>也许连整个世界都……』</span>',
		'effect' => '<span class="ltcrimson b">『但我们还有最后的手段……<br><br>最后的希望。』</span>',
		'desc_skills' => '获得技能「炼狱」：每次发动后使自己先制率-1.5%，命中率、闪避率、发现率、造成伤害-5%（可叠加）。若上一次发动后战斗击杀了15个敌人，则将所有减益变为等量的增益，然后不能再发动此技能',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'719' => '0',
			),
		)
	),
	381 => array(
		'name' => '双料特工',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '啊哈哈哈哈！鸡汤来咯！',
		'effect' => '开局携带毒物说明书和手榴弹',
		'desc_skills' => '进场后会显示为一张随机S-C卡',
		'energy' => 150,
		'valid' => array(
			//在skill720模块处理
			'cardchange' => Array(
				'S_odds' => 30,
				'A_odds' => 30,
				'B_odds' => 20,
				'C_odds' => 20,
				'allow_EB' => true,
				'ignore_cards' => Array(81, 237, 241, 344)//机制上必定选不到自己，这里可以放其他不想被选到的卡
			)
		)
	),
	382 => array(
		'name' => '俏佳人',
		'rare' => 'C',
		'pack' => 'Ranmen',
		'desc' => '阿伟你又在打电动哦，<br>去休息一下好不好',
		'effect' => '可以用笔记本电脑玩最新最潮的游戏<br>——ACFUN大逃杀',
		'energy' => 0,
		'valid' => array(
		  'skills' => array(
				'721' => '0', 
			),
		)
	),
	383 => array(
		'name' => '试雪汉',
		'rare' => 'A',
		'pack' => 'Event Bonus',
		'desc' => '一个程序员走进雪餐厅，要了一杯',
		'effect' => '雪山崩塌的时候，每一片雪花都在勇闯天涯',
		'desc_skills' => '获得战斗技「雪崩」：攻击时发出巨大的响声，命中后将双方<br>包裹和视野里的道具用雪覆盖（使用后获得原先的物品）',
		'energy' => 100,
		'valid' => array(
		  'skills' => array(
				'722' => '0',
			),
		),
		'ignore_global_ach' => 1,//不参与终生成就判定
	),
	384 => array(
		'name' => '野指针',
		'rare' => 'B',
		'pack' => 'Event Bonus',
		'desc' => '警告：地址不合法',
		'effect' => '没事，不是错误',
		'desc_skills' => '你可以进入禁区并在禁区正常行动，但增加禁区时你并不会免疫死亡',
		'energy' => 100,
		'valid' => array(
		  'skills' => array(
				'724' => '0',
			),
		),
		'ignore_global_ach' => 1,
	),
	385 => array(
		'name' => '吉吉国民',
		'rare' => 'A',
		'pack' => 'Standard Pack',
		'desc' => '啊米浴说的道理',
		'effect' => '欧内的手好汉',
		'desc_skills' => '称号固定为偶像歌姬',
		'energy' => 100,
		'valid' => array(
			'club' => '25',
			'skills' => array(
				'10' => '0',
				'11' => '0',
				'12' => '0',
				'87' => '0',
				'88' => '0',
				'89' => '0',
				'90' => '0',
				'91' => '0',
				'92' => '0',
				'93' => '0',
				'94' => '0',
				'1003' => array('learnedsongs' => '1_2'),//很搞
			),
		),
	),
	386 => array(
		'name' => '铁兽战线·姬特',
		'title' => '姬哥',
		'rare' => 'A',
		'pack' => 'Standard Pack',
		'desc' => '厉不厉害你姬哥',
		'effect' => '称号固定为妙手天成',
		'energy' => 100,
		'valid' => array(
			'club' => '26',
			'skills' => array(
				'10' => '0',
				'11' => '0',
				'12' => '0',
				'97' => '0',
				'98' => '0',
				'99' => '0',
				'100' => '0',
				'101' => '0',
				'102' => '0',
				'103' => '0',
			),
		),
	),
	387 => array(
		'name' => '回坑挑战者',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '先氪个直升包',
		'effect' => '开局为30级，并获得8点技能点',
		'desc_skills' => '但是初始经验值不增加',
		'energy' => 150,
		'valid' => array(
			'lvl' => '30',
			'skillpoint' => '+8',
		),
	),
	388 => array(
		'name' => '噬魂者',
		'rare' => 'B',
		'pack' => 'Way of Life',
		'desc' => '拥有吞食灵魂能力的篝火服幸存者',
		'effect' => '做人最重要的是火候',
		'desc_skills' => '开局携带的面包和矿泉水变为灵魂碎片但效耐降低<br>销毁尸体时会得到命体回复类别的灵魂碎片，并可以使用<br>打火机强化灵魂碎片，但食用其他补给品效果-70%',
		'energy' => 100,
		'valid' => array(
			'itm1' => '褪色的灵魂碎片',
			'itmk1' => 'HB',
			'itme1' => '70',
			'itms1' => '10',
			'itmsk1' => '',
			'itm2' => '褪色的灵魂碎片',
			'itmk2' => 'HB',
			'itme2' => '70',
			'itms2' => '10',
			'itmsk2' => '',
			'skills' => array(
				'725' => '0',
			),
		),
	),
	389 => array(
		'name' => '大统领',
		'rare' => 'S',
		'pack' => 'Standard Pack',
		'desc' => '『准备进入地狱吧！』',
		'effect' => '称号固定为铁拳无敌（没有技能「尊严」），且获得妙手天成技能「魂兵」，但只能嵌入斩系武器',
		//'desc_skills' => '',
		'energy' => 100,
		'valid' => array(
			'club' => '19',
			'skills' => array(
				'10' => '0',
				'11' => '0',
				'12' => '0',
				'256' => '0',
				'258' => '0',
				'257' => '0',
				'260' => '0',
				'259' => '0',
				'274' => '0',
				'262' => '0',
				'263' => '0',
				'261' => '0',
				'103' => '0',
				'726' => '0',
			),
		),
	),
	390 => array(
		'name' => '睿智机器人',
		'rare' => 'A',
		'pack' => 'Ranmen',
		'desc' => '喜提四强',
		'effect' => '获得宝石骑士技能「晶环」和战斗技「整理」',
		'desc_skills' => '「晶环」：可以吸收方块道具使自己在攻击时概率视为具有方块对应的属性<br>「整理」：本次攻击最终伤害+10%，「晶环」生效概率永久+5%。消耗50怒气',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'104' => '0',
				'727' => '0',
			),
		),
	),
	391 => array(
		'name' => '手办控',
		'rare' => 'A',
		'pack' => 'Ranmen',
		'desc' => '<span class="ltazure b">“什么，是DTS的周边？<br>给我也整一个！”</span>',
		'effect' => '开局获得一个能开出特殊饰品的盲盒',
		'energy' => 100,
		'valid' => array(
			'itm6' => '礼品盲盒',
			'itmk6' => 'p',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => array(
				'^res_<:comp_itmsk:>{棕色的Howling手办,A,1,1,N,}1^rtype4^reptype1',
				'^res_<:comp_itmsk:>{深蓝色的SAS手办,A,1,1,n,}1^rtype4^reptype1',
				'^res_<:comp_itmsk:>{天青色的Annabelle手办,A,1,1,i,}1^rtype4^reptype1',
				'^res_<:comp_itmsk:>{红色的星海手办,A,1,1,M,}1^rtype4^reptype1',
				'^res_<:comp_itmsk:>{粉色的Sophia手办,A,1,1,w,}1^rtype4^reptype1'
			),
		),
	),
	392 => array(
		'name' => '斯洛蒂的盒子',
		'rare' => 'B',
		'pack' => 'Ranmen',
		'desc' => '某天早晨，你的床头多了一个盒子。<br><br>盒子里传来瓮声瓮气的话音：',
		'effect' => '“喵，把钱放进来有一半概率翻倍，也有一半概率消失，很公平吧喵！”',
		'desc_skills' => '可以不限次数使用',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'728' => '0',
			),
		),
	),
	393 => array(
		'name' => '甜圈挑战者',
		'rare' => 'B',
		'pack' => 'Ranmen',
		'desc' => '可是，你真的很想吃那个甜甜圈……',
		'effect' => '开局大概率获得一个<br>增加生命上限的甜甜圈',
		'desc_skills' => '但也有可能是别的奇怪的东西',
		'energy' => 100,
		'valid' => array(
			'itm6' => '甜甜圈',
			'itmk6' => 'MH',
			'itme6' => array('10','30','50','70','90','110'),
			'itms6' => '1',
			'itmsk6' => array('','','','','','','','','','^res_<:comp_itmsk:>{悔恨,Y,1,1,O,}1^rtype3^reptype1'),
		),
	),
	394 => array(
		'name' => 'roll点挑战者',
		'rare' => 'S',
		'pack' => 'Ranmen',
		'desc' => '梭哈！梭哈！梭哈！！！<br>赢了会所嫩模，输了会所嫩模！',
		'effect' => '开局获得一个只有自己能使用的二十面骰，使用后可将自己的所有技能替换为随机技能',
		'desc_skills' => '生命、攻防、治愈等少数技能不会被替换',
		'energy' => 100,
		'valid' => array(
			'itm6' => '二十面骰',
			'itmk6' => 'Y',
			'itme6' => '1',
			'itms6' => '1',
			'itmsk6' => '',
			'skills' => array(
				'729' => '0',
			),
		),
	),
	395 => array(
		'name' => '麦块挑战者',
		'rare' => 'B',
		'pack' => 'Ranmen',
		'desc' => '接下来我要推荐一款没有圆的游戏',
		'effect' => '开局获得一个特殊的方块',
		'desc_skills' => '这个方块当然是有用的',
		'energy' => 120,
		'valid' => array(
			'rand_sets' => array(
				array(
					'itm6' => '红石方块',
					'itmsk6' => '^res_<:comp_itmsk:>{红色方块,X,1,3,,}1^reptype2^rtype4',
				),
				array(
					'itm6' => '草方块',
					'itmsk6' => '^res_<:comp_itmsk:>{绿色方块,X,1,3,,}1^reptype2^rtype4',
				),
				array(
					'itm6' => '蓝冰方块',
					'itmsk6' => '^res_<:comp_itmsk:>{蓝色方块,X,1,3,,}1^reptype2^rtype4',
				),
				array(
					'itm6' => '泥土方块',
					'itmsk6' => '^res_<:comp_itmsk:>{黄色方块,X,1,3,,}1^reptype2^rtype4',
				),
				array(
					'itm6' => '黄金方块',
					'itmsk6' => '^res_<:comp_itmsk:>{金色方块,X,1,3,,}1^reptype2^rtype4',
				),
				array(
					'itm6' => '煤炭方块',
					'itmsk6' => '^res_<:comp_itmsk:>{黑色方块,X,1,3,,}1^reptype2^rtype4',
				),
				array(
					'itm6' => '石英方块',
					'itmsk6' => '^res_<:comp_itmsk:>{白色方块,X,1,3,,}1^reptype2^rtype4',
				),
				array(
					'itm6' => '雪方块',
					'itmsk6' => 'O^res_<:comp_itmsk:>{白色方块,X,1,3,,}1^reptype2^rtype4',
				),
				array(
					'itm6' => '玻璃方块',
					'itmsk6' => '^res_<:comp_itmsk:>{水晶方块,X,1,3,,}1^reptype2^rtype4',
				),
			),
			'itmk6' => 'X',
			'itme6' => '1',
			'itms6' => '1',
		),
	),
	396 => array(
		'name' => '梅尔菲的魔术师',
		'rare' => 'B',
		'pack' => 'Ranmen',
		'desc' => '童话动物之森有好多个性丰富可爱的动物们——这是小兔子，这是小猫，这是小狗，这是兽王阿尔法，这是天霆号阿宙斯',
		'effect' => '开局装备一个礼帽。你放入储物装备的道具会变成随机的小动物',
		'energy' => 100,
		'valid' => array(
			'arh' => '魔术礼帽',
			'arhk' => 'DH',
			'arhe' => '33',
			'arhs' => '15',
			'arhsk' => '^st1^vol1',
			'skills' => array(
				'730' => '0',
			),
		),
	),
	397 => array(
		'name' => '韭菜',
		'rare' => 'B',
		'pack' => 'Ranmen',
		'desc' => '我相信今天是大奇迹日，<br>一定会涨的！',
		'effect' => '今天回到家，煮了点面吃',
		'desc_skills' => '可以在『欺货市场』中买入或卖出特定道具，这些道具的价格会随机涨跌',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'731' => '0',
			),
		),
	),
	398 => array(
		'name' => '水管工',
		'rare' => 'M',
		'pack' => 'Event Bonus',
		'desc' => '你是新来的水管工小马，总裁安排你去修好正在漏雪的水管',
		'effect' => '上一任水管工小林跑路前，给你留下了一张纸条',
		'desc_skills' => '开局获得一张纸条和三根漏水的水管。',
		'energy' => 0,
		'valid' => array(
			'itm0' => '提示纸条SNOW',
			'itmk0' => 'Z',
			'itme0' => '1',
			'itms0' => '1',
			'itm4' => '漏水的雪管',
			'itmk4' => 'Z',
			'itme4' => '1',
			'itms4' => '1',
			'itm5' => '漏雪的水管',
			'itmk5' => 'Z',
			'itme5' => '1',
			'itms5' => '1',
			'itm6' => '漏雪的雪管',
			'itmk6' => 'Z',
			'itme6' => '1',
			'itms6' => '1',
			'rand_sets' => array(
				array(
					'itmsk4' => 'O^skflag732',
					'itmsk5' => 'O',
					'itmsk6' => 'O'
				),
				array(
					'itmsk4' => 'O',
					'itmsk5' => 'O^skflag732',
					'itmsk6' => 'O'
				),
				array(
					'itmsk4' => 'O',
					'itmsk5' => 'O',
					'itmsk6' => 'O^skflag732'
				)
			),
			'skills' => array(
				'732' => '0',
			),
		),
		'ignore_global_ach' => 1,
	),
	399 => array(
		'name' => '随面',
		'rare' => 'C',
		'pack' => 'Ranmen',
		'desc' => '生活就是一碗面',
		'effect' => '可能没有调料包，也可能没有叉子',
		'desc_skills' => '开局获得一些补给品，也可能有赠送的蛋白质',
		'energy' => 0,
		'valid' => array(
			'skills' => array(
				'735' => '0', 
			),
			'itm4' => '调料包',
			'itmk4' => 'HH',
			'itme4' => '30',
			'itms4' => '1',
			'itmsk4' => '',
			'itm5' => '蔬菜包',
			'itmk5' => 'HS',
			'itme5' => '30',
			'itms5' => '1',
			'itmsk5' => '',
			'itm6' => '面饼',
			'itmk6' => 'HB',
			'itme6' => '120',
			'itms6' => '1',
			'itmsk6' => '',
			'rand_sets' => array(
				array(
				),
				array(
					'itm3' => '叉子',
					'itmk3' => 'WK',
					'itme3' => '10',
					'itms3' => '10',
					'itmsk3' => '',
				),
				array(
					'itm3' => '卡牌包',
					'itmk3' => 'VO2',
					'itme3' => '1',
					'itms3' => '1',
					'itmsk3' => '',
				),
				array(
					'itm3' => '「增殖的G」',
					'itmk3' => 'PB02',
					'itme3' => '50',
					'itms3' => '20',
					'itmsk3' => '',
					'skills' => array(
						'735' => '1', 
					),
				),
				array(
					'itm3' => '「增殖的G」',
					'itmk3' => 'PB02',
					'itme3' => '50',
					'itms3' => '20',
					'itmsk3' => '',
					'skills' => array(
						'735' => '1', 
					),
				),
				array(
					'itm4' => '',
					'itmk4' => '',
					'itme4' => '0',
					'itms4' => '0',
				),
				array(
					'itm5' => '',
					'itmk5' => '',
					'itme5' => '0',
					'itms5' => '0',
				),
			),
		),
	),
	400 => array(
		'name' => '增殖挑战者',
		'rare' => 'A',
		'pack' => 'Way of Life',
		'desc' => '我是谁？谁是我？<br>是谁杀了我，而我又杀了谁？',
		'effect' => '是我，杀了，我！',
		'desc_skills' => '你击杀和销毁尸体的角色名字在进行状况中显示为与你相同',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'736' => '1', 
			),
		),
	),
	401 => array(
		'name' => '暗影卫队',
		'rare' => 'A',
		'pack' => 'Best DOTO',
		'desc' => '<span style="opacity:0.005;">检测到关键词，暗影卫队出动！</span>',
		'effect' => '<span style="opacity:0.005;">你攻击造成伤害、击杀角色和销毁尸体不会显示在进行状况中</span>',
		'desc_skills' => '你攻击造成伤害、击杀角色和销毁尸体不会显示在进行状况中',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'736' => '0', 
			),
		),
	),
	402 => array(
		'name' => '首席雕花官',
		'rare' => 'B',
		'pack' => 'Event Bonus',
		'desc' => '去年我们的todolist字数增加了1151%，记录在案的big数增加了2333%，这是工作流程规范化上的<br>可喜进步',
		'effect' => '今年要增加总结反思会的次数，小林你下午定个会议室',
		'desc_skills' => '可以将补给品道具变为黄色雏菊/歌魂增加/120/1/天然，<br>但使用效果不变',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'737' => '0',
			),
		),
		'ignore_global_ach' => 1,
	),
	403 => array(
		'name' => '幻境调查员',
		'rare' => 'A',
		'pack' => 'Standard Pack',
		'desc' => '侦查、聆听、图书馆',
		'effect' => '称号固定为深渊学者',
		'energy' => 100,
		'valid' => array(
			'club' => '27',
			'skills' => array(
				'10' => '0',
				'11' => '0',
				'12' => '0',
				'107' => '0',
				'108' => '0',
				'109' => '0',
				'110' => '0',
				'111' => '0',
				'112' => '0',
				'113' => '0',
			),
		),
	),
	//404暂时留空
	405 => array(
		'name' => 'skip党',
		'rare' => 'C',
		'pack' => 'Standard Pack',
		'desc' => '我不知道这是哪，我不知道我是谁',
		'effect' => '我只知道我要大开杀戒',
		'desc_skills' => '开局全系熟练度+8，但会跳过开局剧情和地点事件的显示',
		'energy' => 0,
		'valid' => array(
			'wp' => '+8',
			'wk' => '+8',
			'wc' => '+8',
			'wg' => '+8',
			'wf' => '+8',
			'wd' => '+8',
			'skills' => array(
				'740' => '0',
			),
		),
	),
	406 => array(
		'name' => '老千',
		'rare' => 'A',
		'pack' => 'Ranmen',
		'desc' => '他有一个灌铅的骰子',
		'effect' => '获得技能「机变」：连击属性更容易<br>命中较多次',
		'energy' => 120,
		'valid' => array(
			'skills' => array(
				'741' => '0',
			),
		),
	),
	
	1000 => array(
		'name'=>'萌新',
		'rare'=>'A',
		'pack'=>'Stealth',
		'desc'=>'<span class="vermilion b">“这是「卡片」，是「虚拟幻境」给与的能力的其中一个载体。<br>不同的卡片拥有不同的效果，你可以之后自行探究。”</span>',
		'effect'=>'<span class="vermilion b">“而现在，如果你不想落地成盒的话，就不要东张西望，认真听我说。”</span>',
		'energy'=>0,
		'valid' => array(
			'pls' => 33,
			'itm3' => '解毒剂',
			'itmk3' => 'Cp',
			'itme3' => '1',
			'itms3' => '10',
			'itmsk3' => '',
			'skills'=>array(
				'1000'=>'0'
			)
		)
	),
	1001 => array(
		'name'=>'肉鸽来客',
		'rare'=>'A',
		'pack'=>'hidden',
		'desc'=>'今天凹了一晚上，想要的卡一张没有',
		'effect'=>'我草，怎么天亮了？',
		'energy'=>0,
		'valid' => array(
			'cardchange' => Array(
				'real_random' => true,//真随机，所有卡选1张
				'perm_change' => true,//永久改变，换卡之后不会再把card字段切回来，也不会按这张卡判定成就
				'forced' => Array(),//无视概率强制加入选择的卡
				'ignore_cards' => Array(81, 237, 300, 344, 381)//机制上必定选不到自己，这里可以放其他不想被选到的卡
			)
		)
	),
);
}
?>