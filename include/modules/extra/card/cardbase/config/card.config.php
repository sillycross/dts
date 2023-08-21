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
	'Balefire Rekindle',
	'Cyber Zealots',
	'Event Bonus',
	
	'Stealth',
	'hidden'
);
//卡包介绍
$packdesc = array(
	'Standard Pack' => '基本称号卡集。',
	'Crimson Swear' => '以游戏阵营「红杀」组织以及其马甲「金龙通讯社」为主题的卡集。',
	'Top Players' => '以本游戏发展史上那些著名玩家和重要开发者为纪念/捏他对象的卡集。',
	'Way of Life' => '大杂烩，主要以游戏方式以及同类游戏为捏他对象的卡集。',
	'Best DOTO' => '以电竞元素和电竞圈为吐槽对象的卡集。',
	'Balefire Rekindle' => '以游戏版本「复燃」的新增NPC角色和游戏设定为主题的卡集。',
	'Event Bonus' => '其他一些零散成就和活动奖励卡。',
	'Cyber Zealots' => '以赛博朋克和网络梗为捏他对象的卡集。',
	'Stealth' => '一些需要显示卡片介绍的隐藏卡',
	'hidden' => '隐藏卡片，不会悬浮显示卡片介绍，如果你看到这句话请联系天然呆管理员',
);
//不参与抽卡的卡包
$pack_ignore_kuji = Array('Balefire Rekindle','Event Bonus');
//卡包实装的时间戳，可以用来隐藏卡包
$packstart = array(
	'Cyber Zealots' => 4476654671,
	'Stealth' => 4476654671,
	'hidden' => 4476654671,
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
			'itm5' => '思念数据',
			'itmk5' => 'ME',
			'itme5' => '9',
			'itms5' => '11',
			'itmsk5' => '',
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
		'pack' => 'Top Players',
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
		'pack' => 'Top Players',
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
			'skillpoint' => '3',
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
		'pack' => 'Top Players',
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
		'effect' => '获得技能「疾走」：<br>被发现率降低15%，爆系和斩系伤害提高20%',
		'energy' => 50,
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
		'desc_skills' => '获得技能「烈击」<br>「烈击」：战斗时有60%概率提高物理伤害75%',
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
		'effect' => '获得技能「红石」：<br>生命值高于一半则把防御力的一半加到攻击力上，反之则把攻击力的一半加到防御力上',
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
		'effect' => '获得技能「疾走」：<br>被发现率降低15%，爆系和斩系伤害提高20%',
		'energy' => 50,
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
		'desc' => '在时空特使里默默无闻的工作人员。<br>某次事件之后就消失了',
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
		'pack' => 'Top Players',
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
		'pack' => 'Top Players',
		'desc' => '著名的小黄系列玩家，设计了《小黄的大师球》和初版游戏王的合成',
		'effect' => '获得技能「鹰眼」：<br>600秒内必中（爆系和重枪无效），发动时每有1点投熟额外延长1秒，每局只能使用1次。',
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
			'hp' => '+400',
			'mhp' => '+400',
			'club' => '17',
			'skills' => array(
				'12' => '0', 
			)
		)
	),
	80 => array(
		'name' => '兵马俑',
		'rare' => 'B',
		'pack' => 'Standard Pack',
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
			'cardchange' => Array(
				'S_odds' => 20,
				'A_odds' => 40,
				'B_odds' => 20,
				'C_odds' => 20,
				'allow_EB' => true,//开启后会把Event Bonus等需要特殊方式才能获得的卡也一并考虑
				'ignore_cards' => Array(237)//机制上必定选不到自己，这里可以放其他不想被选到的卡
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
		'energy' => 250,
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
		'effect' => '获得技能「死线」：发动后30秒内免疫一切战斗伤害，每局只能使用1次',
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
		'rare' => 'S',
		'pack' => 'Top Players',
		'desc' => 'AC大逃杀元老人物之一，<br>擅长上班摸鱼、下班挖坑',
		'effect' => '获得技能「挖坑」，但不能选到肌肉兄贵称号',
		'desc_skills' => '「挖坑」：在你当前地点放置两个毒性陷阱，效果值为基础攻击力x距上次挖坑的分钟数(最多为3)<br>此外你不会遭遇自己放置的陷阱',
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
		'rare' => 'A',
		'pack' => 'Standard Pack',
		'desc' => '2016年，<br>大逃杀战场被核子的火焰笼罩！<br>草木干枯，大地开裂，<br>拳法家像死绝了一样',
		'effect' => '但是拳法家并没有死绝！',
		'desc_skills' => '称号固定为铁拳无敌',
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
		'pack' => 'Top Players',
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
		'pack' => 'Top Players',
		'desc' => '她说：“要有大逃杀。”<br>然后她就平地摔了',
		'effect' => '开局携带《ACFUN大逃杀原案》。',
		'desc_skills' => '《ACFUN大逃杀原案》：使用后获得100点全系熟练度，但是之后你会更加倒霉',
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
		'energy' => 100,
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
		'desc_skills' => '找到并装备【巨大灯泡】以后，你将免疫一切战斗和陷阱伤害。<br>但是如果你装备、包裹及视野中都不存在【巨大灯泡】，你会立即从这局游戏中消失',
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
		'desc_skills' => '获得技能「鱼弹」：战斗技，本次物理伤害变成0，但对方随机一件防具的耐久值下降你的武器效果值，每局只能发动2次',
		'energy' => 100,
		'valid' => array(
			'skills' => array(
				'517' => '0', 
			),
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
		'pack' => 'Way of Life',
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
			'itm5' => array('增幅设备','某种电子零件','手机','笔记本电脑','探测器电池'),
			'itmk5' => 'X',
			'itme5' => '1',
			'itms5' => '1',
			'itmsk5' => '',
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
			'itm3' => array('捕鼠夹','捕鼠笼','捕鼠胶','粘鼠板','电子灭鼠器','灭鼠无人机'),
			'itmk3' => 'TN',
			'itme3' => '25',
			'itms3' => '3',
			'itmsk3' => '',
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
		)
	),
	181 => array(
		'name' => '幽灵 帕比丝麦尔',
		'title' => '幽灵',
		'rare' => 'S',
		'pack' => 'Event Bonus',
		'desc' => '<span style="font-size:10px">　　说到幽灵，Big 52的幽灵传说仍在继续，尽管它现在比起事实，更像是个噩梦夜的鬼故事。据说，当太阳落山、月亮升起以后，会有一个鬼影踩着滑板车在大路上游荡，随风而来，寂静无声。它直奔坏蛋而去，追索着奴隶贩子、劫掠者和阴谋家的性命。至少有两队劫掠者真的死在了大路上。他们外表毫发无伤，武器上膛、如临大敌，却依然横尸街头，原因成谜，凶手却没有留下一丝线索。说了这么多，我只想问你一个问题…</span>',
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
			'itm3' => Array('小苹果', 'Alicemagic', 'Crow Song', '驱寒颂歌', '雨だれの歌', '快说小仓唯唱歌贼！好！听！', '黄鸡之歌'),
			'itmk3' => 'ss',
			'itme3' => '1',
			'itms3' => '1',
			'itmsk3' => 'z',
		)
	),
	189 => array(
		'name' => '锁血挑战者',
		'rare' => 'C',
		'pack' => 'Way of Life',
		'desc' => '我相信着我的卡组！',
		'effect' => '开局HP为1点，<br>额外携带1份游戏王卡牌包',
		'energy' => 0,
		'valid' => array(
			'hp' => 1,
			'itm3' => '游戏王卡包',
			'itmk3' => 'ygo',
			'itme3' => '1',
			'itms3' => '1',
			'itmsk3' => '',
		)
	),
	190 => array(
		'name' => 'ycNaN',
		'rare' => 'A',
		'pack' => 'Event Bonus',
		'desc' => '过去经历一切不明的女骇客，<br>其typing能力就算在糟糕级骇客中也实际强大！',
		'effect' => '开局经验、金钱和怒气都是77点。<br>如果你上一次操作在程序执行时<br>没有顺利完成，你获得7点经验和全系熟练，还会给这个游戏的天然呆程序员发一封站内信。',
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
		  'itm5' => Array('社畜专用的ID卡', '社员砖用的ID卡', '社长专用的ID卡', '社恐专用的ID卡', '社员专甩的ID卡',
		  '社员不用的ID卡', '社死专用的ID卡', '社保专用的ID卡', '社精专用的ID卡', '社会专用的ID卡',
		  '社员专用的IC卡', '社员专用的IP卡', '社员专用的IQ卡', '社员专用的1D卡', '社员专用的IO卡', '社员专用的lD卡', '社员专用的|D卡', '社员专用的ＩＤ卡',
		  '社元专用的ID卡', '社员专角的ID卡', '社贡专用的ID卡', '社员专卖的ID卡', '社员专享的ID卡',
		  '社员专精的ID卡', '涩员专用的ID卡', '杜员专用的ID卡', '壮员专用的ID卡', '社员专月的ID卡',),
			'itmk5' => 'Y',
			'itme5' => '1',
			'itms5' => '1',
			'itmsk5' => '',
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
		'name' => '占位测试符',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '超过48张卡占位测试',
		'effect' => '超过48张卡占位测试',
		'energy' => 0,
		'valid' => array(
			'hp' => 99,
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
		'effect' => '获得技能「无垠」：战斗中死亡时有50%概率复活，但之后因此复活的概率减半',
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
		'effect' => '获得技能「时停」，但不能赢得幸存胜利',
		'desc_skills' => '「时停」：消耗30点怒气，能停止整个幻境的时间3秒钟，冷却时间30秒',
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
		'effect' => '获得技能「胜天」：你的防御和抹消属性失效概率减半，被贯穿概率减半',
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
			'itm3' => '超天新龙 异色眼革命龙★12',
			'itmk3' => 'TN12',
			'itme3' => '800',
			'itms3' => '1',
			'itmsk3' => '',
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
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '一种神奇的电子界生物，擅长版本控制和同性交友',
		'effect' => '开局携带建立分叉和拉取请球',
		//'desc_skills' => '',
		'energy' => 0,
		'valid' => array(
		  'wep' => '建立分叉',
			'wepk' => 'WK',
			'wepe' => '120',
			'weps' => '5',
			'wepsk' => 'eg',
			'itm3' => '拉取请球',
			'itmk3' => 'DH',
			'itme3' => '120',
			'itms3' => '5',
			'itmsk3' => 'eg',
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
		'pack' => 'Cyber Zealots',
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
			'itm3' => Array('垃圾','光盘','破鞋','塑料袋','石头','树枝','骨头','原型武器K','鲨鱼鳍','凸眼鱼'),
			'itmk3' => Array('X','Y','HH','HS','HB','PB','PB2'),
			'itme3' => '70',
			'itms3' => '5',
			'itmsk3' => 'z',
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
		'rare' => 'C',
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
		'desc' => '弹幕网站上那些翻唱名曲毁耳不倦的职人',
		'effect' => '开局装备销魂之歌',
		'energy' => 0,
		'valid' => array(
		  'wep' => '销魂之歌',
			'wepk' => 'WK',
			'wepe' => '50',
			'weps' => '50',
			'wepsk' => '',
		)
	),
	225 => array(
		'name' => '字幕职人',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '弹幕网站上那些把弹幕当积木摆的职人',
		'effect' => '开局装备神字幕',
		'energy' => 0,
		'valid' => array(
		  'wep' => '神字幕',
			'wepk' => 'WC',
			'wepe' => '50',
			'weps' => '50',
			'wepsk' => '',
		)
	),
	226 => array(
		'name' => '搬运职人',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '弹幕网站上那些搬运其他网站视频的职人',
		'effect' => '开局装备搬运之拳',
		'energy' => 0,
		'valid' => array(
		  'wep' => '搬运之拳',
			'wepk' => 'WP',
			'wepe' => '50',
			'weps' => '50',
			'wepsk' => '',
		)
	),
	227 => array(
		'name' => '专业喷子',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '一个普通的喷子，全身上下只有嘴是硬的',
		'effect' => '开局装备嘴炮',
		'energy' => 0,
		'valid' => array(
		  'wep' => '嘴炮',
			'wepk' => 'WG',
			'wepe' => '50',
			'weps' => '50',
			'wepsk' => '',
		)
	),
	228 => array(
		'name' => '小鬼',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '他很热衷于网络80',
		'effect' => '开局装备网暴',
		'energy' => 0,
		'valid' => array(
		  'wep' => '网暴',
			'wepk' => 'WD',
			'wepe' => '50',
			'weps' => '50',
			'wepsk' => '',
		)
	),
	229 => array(
		'name' => '车万人',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '车万人下雨不用伞',
		'effect' => '开局装备符卡',
		'energy' => 0,
		'valid' => array(
		  'wep' => '符卡',
			'wepk' => 'WF',
			'wepe' => '30',
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
		  'itm3' => '『我是说在座的各位都是垃圾』',
			'itmk3' => 'Y',
			'itme3' => '1',
			'itms3' => '1',
			'itmsk3' => '',
		)
	),
	231 => array(
		'name' => '卡片男',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '看，这个男人捡到一张神秘的小卡片',
		'effect' => '开局携带一份卡牌包',
		'energy' => 0,
		'valid' => array(
		  'itm3' => '卡牌包',
			'itmk3' => 'VO9',
			'itme3' => '1',
			'itms3' => '1',
			'itmsk3' => '',
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
		'pack' => 'Cyber Zealots',
		'desc' => '据说林无月前两年已经死了，<br>现在管理幻境的都是她的亲友团<br>「阿林百人众」。<br>她临死前给每个人发了一个小本子，里面是一百多页如何扮演她的心得。',
		'effect' => '开局能复制并获取场上存活NPC的一项技能',
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
		'effect' => '<s>法力+3</s><br>获得锡安成员技能「网瘾」、「破解」及「探测」',
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
		  'itm3' => '黯淡的银色盒子',
			'itmk3' => 'p2',
			'itme3' => '1',
			'itms3' => '1',
			'itmsk3' => '',
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
		  'itm3' => '模因原液',
			'itmk3' => Array('MA','MD','MH','MS','ME','MV'),
			'itme3' => '1',
			'itms3' => '5',
			'itmsk3' => '',
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
				'ignore_cards' => Array(81)//机制上必定选不到自己，这里可以放其他不想被选到的卡
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
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '爬吗？',
		'effect' => '爬',
		'desc_skills' => '开局能复制并获得场上存活的NPC身上的一件道具',
		'energy' => 100,
		'valid' => array(
		  'skills' => array(
				'529' => '1', 
			),
		)
	),
	241 => array(
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
		'energy' => 100,
		'valid' => array(
		  'itm5' => '某种电子零件',
			'itmk5' => Array('HB','HB','HB','HB','HB','HB','HB','HB','HB','PB2'),
			'itme5' => '199',
			'itms5' => '5',
			'itmsk5' => '',
		)
	),
	245 => array(
		'name' => '网抑云',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '这么晚还在打大逃杀，你一定也很寂寞吧',
		'effect' => '开局携带寂寞',
		//'desc_skills' => '',
		'energy' => 100,
		'valid' => array(
		  'itm5' => '寂寞',
			'itmk5' => Array('WP','WK','WD','WC','WG','WF','WB','PB2','DB','DH','DA','DF','A'),
			'itme5' => '76',
			'itms5' => '5',
			'itmsk5' => '',
		)
	),
	246 => array(
		'name' => '箭怔人',
		'rare' => 'C',
		'pack' => 'Cyber Zealots',
		'desc' => '顺我者屠殿大佬，逆我者无名沙包',
		'effect' => '开局携带大量箭矢',
		//'desc_skills' => '',
		'energy' => 100,
		'valid' => array(
		  'itm5' => '箭症',
			'itmk5' => 'GA',
			'itme5' => '1',
			'itms5' => '44',
			'itmsk5' => Array('e', 'p', 'u', 'i', 'w'),
		)
	),
	
	
	1000 => array(
		'name'=>'萌新',
		'rare'=>'A',
		'pack'=>'Stealth',
		'desc'=>'<span class="linen" style="font-weight:bold">“这是「卡片」，是「虚拟幻境」给与的能力的其中一个载体。<br>不同的卡片拥有不同的效果，你可以之后自行探究。”</span>',
		'effect'=>'<span class="linen" style="font-weight:bold">“而现在，如果你不想落地成盒的话，就不要东张西望，认真听我说。”</span>',
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
		'energy'=>120,
		'valid' => array(
			'cardchange' => Array(
				'real_random' => true,//真随机，所有卡选1张
				'perm_change' => true,//永久改变，换卡之后不会再把card字段切回来，也不会按这张卡判定成就
				'forced' => Array(),//无视概率强制加入选择的卡
				'ignore_cards' => Array(81, 237)//机制上必定选不到自己，这里可以放其他不想被选到的卡
			)
		)
	),
);
}
?>