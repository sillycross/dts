<?php

namespace skill56
{
	$skill56_npc = array
	(
		'mode' => 1,
		'num' => 0,
		'pass' => 'fklnzg20',
		'bid' => 0,
		'inf' => '',
		'rage' => 100,
		'tactic' => 3,
		'killnum' => 0,
		'teamID' => '',
		'teampsss' => '',
		'pls' => 99,
		'art' => '契约书',
		'artk' => 'A',
		'arte' => 1,
		'arts' => 1,
		'artsk' => 'H',
		'money' => $skill56_need,
		'sub' => array
		(
			0 => array
			(
			'probability' => 500,
			//佣兵每分钟的工资，移动需要支付的两分钟的工资
			'mercsalary' => 500,	//肥宅花钱如流水，太肥懒得动
			//解雇后的行动，0为呆在原地，1为离开战场
			//即使呆在原地，也不会与雇主进入战斗界面（因为设计意图就是强力佣兵跑路，弱渣留下继续给人送钱）
			//20240103更改：现在改为0为随机移动到一个地点（不包括无月和英灵殿），1为离开战场（从数据库删除这个npc）；可以与被解雇的佣兵正常进战斗
			'mercfireaction' => 0,	
			'club' => 0,
			'name' => '肥宅',
			'icon' => 150,
			'pose'=> 0,
			'gd' => 'm',
			'mhp' => 300,
			'msp' => 400,
			'att' => 5,
			'def' => 50,
			'lvl' => 1,
			'skill' => 5,
			'wep' => '我好兴奋啊！',
			'wepk' => 'WF',
			'wepe' => 7,
			'weps' => 999,
			'wepsk' => 'w',
			'arb' => '不合身的衬衫',
			'arbk' => 'DB',
			'arbe' => 5,
			'arbs' => 20,
			'arbsk' => '',
			'arh' => '包头巾',
			'arhk' => 'DH',
			'arhe' => 5,
			'arhs' => 12,
			'arhsk' => '',
			'arf' => '人字拖',
			'arfk' => 'DF',
			'arfe' => 2,
			'arfs' => 5,
			'arfsk' => '',
			'ara' => '佐佑酱',
			'arak' => 'DA',
			'arae' => 5,
			'aras' => 2,
			'arask' => 'c',
			'description' => '肥宅，随处可见的肥胖宅男。没有任何威胁。',
			),
			1 => array
			(
			'probability' => 200,
			'mercsalary' => 20,
			'mercfireaction' => 0,	
			'club' => 17,
			'name' => '春原 阳平',
			'icon' => 151,
			'pose'=> 0,
			'gd' => 'm',
			'mhp' => 400,
			'msp' => 400,
			'att' => 120,
			'def' => 250,
			'lvl' => 5,
			'skill' => 100,
			'wep' => '马桶盖子',
			'wepk' => 'WP',
			'wepe' => 50,
			'weps' => 50,
			'wepsk' => '',
			'arb' => '光坂高校校服',
			'arbk' => 'DB',
			'arbe' => 22,
			'arbs' => 22,
			'arbsk' => '',
			'arh' => '马桶盖子',
			'arhk' => 'DH',
			'arhe' => 22,
			'arhs' => 22,
			'arhsk' => '',
			'arf' => '光坂高校校裤',
			'arfk' => 'DF',
			'arfe' => 22,
			'arfs' => 22,
			'arfsk' => '',
			'ara' => '马桶盖子',
			'arak' => 'DA',
			'arae' => 22,
			'aras' => 22,
			'arask' => '',
			'description' => '春原 阳平，马桶盖子专家。',
			),
			2 => array
			(
			'probability' => 200,
			'mercsalary' => 30,
			'mercfireaction' => 0,	
			'club' => 11,
			'name' => '真猎人',
			'icon' => 152,
			'pose'=> 0,
			'gd' => 'm',
			'mhp' => 850,
			'msp' => 400,
			'att' => 120,
			'def' => 450,
			'lvl' => 5,
			'skill' => 140,
			'wep' => '破解的二手PSP2000',
			'wepk' => 'WP',
			'wepe' => 200,
			'weps' => 20,
			'wepsk' => 'e',
			'arb' => '真猎人的追求',
			'arbk' => 'DB',
			'arbe' => 80,
			'arbs' => 80,
			'arbsk' => '',
			'arh' => '真猎人的坚持',
			'arhk' => 'DH',
			'arhe' => 80,
			'arhs' => 80,
			'arhsk' => '',
			'arf' => '真猎人的倔强',
			'arfk' => 'DF',
			'arfe' => 80,
			'arfs' => 80,
			'arfsk' => '',
			'ara' => '真猎人的信念',
			'arak' => 'DA',
			'arae' => 80,
			'aras' => 80,
			'arask' => '',
			'description' => '真猎人，只玩破解中文版怪物猎人的真玩家。',
			),
			3 => array
			(
			'probability' => 200,
			'mercsalary' => 50,
			'mercfireaction' => 0,	
			'club' => 9,
			'name' => '彩虹独角兽',
			'icon' => 153,
			'pose'=> 2,
			'gd' => 'f',
			'mhp' => 1000,
			'msp' => 1000,
			'att' => 350,
			'def' => 650,
			'lvl' => 17,
			'skill' => 200,
			'wep' => '友谊魔法',
			'wepk' => 'WF',
			'wepe' => 200,
			'weps' => 200,
			'wepsk' => 'x',
			'arb' => '诚实与魔法',
			'arbk' => 'DB',
			'arbe' => 120,
			'arbs' => 80,
			'arbsk' => '',
			'arh' => '忠诚与慷慨',
			'arhk' => 'DH',
			'arhe' => 120,
			'arhs' => 80,
			'arhsk' => '',
			'arf' => '小马的腿',
			'arfk' => 'DF',
			'arfe' => 120,
			'arfs' => 80,
			'arfsk' => '',
			'ara' => '善良与欢笑',
			'arak' => 'DA',
			'arae' => 120,
			'aras' => 80,
			'arask' => '',
			'description' => '彩虹独角兽，在宅男中拥有很高人气的小动物。会使用神奇的魔法。',
			),
			4 => array
			(
			'probability' => 150,
			'mercsalary' => 80,
			'mercfireaction' => 0,	
			'club' => 9,
			'name' => '海星王',
			'icon' => 154,
			'pose'=> 2,
			'gd' => 'm',
			'mhp' => 1600,
			'msp' => 1000,
			'att' => 250,
			'def' => 650,
			'lvl' => 17,
			'skill' => 200,
			'wep' => '黑魔导女孩',
			'wepk' => 'WC06',
			'wepe' => 250,
			'weps' => 300,
			'wepsk' => 'r',
			'arb' => '千年积木',
			'arbk' => 'DB',
			'arbe' => 170,
			'arbs' => 80,
			'arbsk' => '',
			'arh' => '法老之眼',
			'arhk' => 'DH',
			'arhe' => 160,
			'arhs' => 80,
			'arhsk' => '',
			'arf' => '海鲜之力',
			'arfk' => 'DF',
			'arfe' => 160,
			'arfs' => 80,
			'arfsk' => '',
			'ara' => '神抽之手',
			'arak' => 'DA',
			'arae' => 200,
			'aras' => 80,
			'arask' => 'x',
			'description' => '海星王，沉迷卡牌游戏的洗剪吹不良少年。有精神分裂症。',
			),
			5 => array
			(
			'probability' => 150,
			'mercsalary' => 90,
			'mercfireaction' => 0,	
			'club' => 9,
			'name' => '红帽少年',
			'icon' => 155,
			'pose'=> 2,
			'gd' => 'm',
			'mhp' => 1500,
			'msp' => 1000,
			'att' => 450,
			'def' => 450,
			'lvl' => 19,
			'skill' => 400,
			'wep' => '黄色老鼠',
			'wepk' => 'WC',
			'wepe' => 600,
			'weps' => 300,
			'wepsk' => 'e',
			'arb' => '蓝色马甲',
			'arbk' => 'DB',
			'arbe' => 200,
			'arbs' => 80,
			'arbsk' => 'G',
			'arh' => '红色鸭舌帽',
			'arhk' => 'DH',
			'arhe' => 220,
			'arhs' => 80,
			'arhsk' => 'FD',
			'arf' => '牛仔裤',
			'arfk' => 'DF',
			'arfe' => 200,
			'arfs' => 80,
			'arfsk' => 'a',
			'ara' => '露指手套',
			'arak' => 'DA',
			'arae' => 200,
			'aras' => 80,
			'arask' => 'P',
			'description' => '红帽少年，周游日本开后宫的年轻动物学家。',
			),
			6 => array
			(
			'probability' => 180,
			'mercsalary' => 100,
			'mercfireaction' => 1,	
			'club' => 3,
			'name' => '海豹部队',
			'icon' => 156,
			'pose'=> 2,
			'gd' => 'm',
			'mhp' => 1400,
			'msp' => 1400,
			'att' => 750,
			'def' => 750,
			'lvl' => 25,
			'skill' => 400,
			'wep' => 'HK MP5',
			'wepk' => 'WG',
			'wepe' => 220,
			'weps' => 600,
			'wepsk' => 'r',
			'arb' => '战术背心',
			'arbk' => 'DB',
			'arbe' => 300,
			'arbs' => 80,
			'arbsk' => 'GK',
			'arh' => '战术钢盔',
			'arhk' => 'DH',
			'arhe' => 300,
			'arhs' => 80,
			'arhsk' => 'GD',
			'arf' => '迷彩军裤',
			'arfk' => 'DF',
			'arfe' => 300,
			'arfs' => 80,
			'arfsk' => '',
			'ara' => '战斗手套',
			'arak' => 'DA',
			'arae' => 250,
			'aras' => 80,
			'arask' => 'GP',
			'description' => '海豹部队，希望国维护世界和平的重要力量。',
			),
			7 => array
			(
			'probability' => 120,
			'mercsalary' => 100,
			'mercfireaction' => 1,	
			'club' => 9,
			'name' => '圣德太子',
			'icon' => 157,
			'pose'=> 2,
			'gd' => 'f',
			'mhp' => 5500,
			'msp' => 2400,
			'att' => 550,
			'def' => 850,
			'lvl' => 25,
			'skill' => 600,
			'wep' => '十七条黄金传染病',
			'wepk' => 'WF',
			'wepe' => 200,
			'weps' => 600,
			'wepsk' => 'r',
			'arb' => '星辰披风',
			'arbk' => 'DB',
			'arbe' => 200,
			'arbs' => 80,
			'arbsk' => 'Fa',
			'arh' => '日和耳机',
			'arhk' => 'DH',
			'arhe' => 200,
			'arhs' => 80,
			'arhsk' => 'W',
			'arf' => '华贵长裙',
			'arfk' => 'DF',
			'arfe' => 200,
			'arfs' => 80,
			'arfsk' => '',
			'ara' => '金色缠腕',
			'arak' => 'DA',
			'arae' => 150,
			'aras' => 80,
			'arask' => '',
			'description' => '圣德太子，带着奇怪耳机的偏执狂。必杀是飞鸟文化无力回天。',
			),
			8 => array
			(
			'probability' => 100,
			'mercsalary' => 125,
			'mercfireaction' => 1,	
			'club' => 3,
			'name' => 'FV4202',
			'icon' => 158,
			'pose'=> 2,
			'gd' => 'm',
			'mhp' => 5000,
			'msp' => 2400,
			'att' => 850,
			'def' => 350,
			'lvl' => 20,
			'skill' => 500,
			'wep' => '105mm Royal L7',
			'wepk' => 'WG',
			'wepe' => 500,
			'weps' => 600,
			'wepsk' => 'cd',
			'arb' => '坦克装甲',
			'arbk' => 'DB',
			'arbe' => 400,
			'arbs' => 180,
			'arbsk' => 'a',
			'arh' => '炮盾',
			'arhk' => 'DH',
			'arhe' => 400,
			'arhs' => 180,
			'arhsk' => 'D',
			'arf' => '重型履带',
			'arfk' => 'DF',
			'arfe' => 400,
			'arfs' => 180,
			'arfsk' => '',
			'ara' => '坦克装甲',
			'arak' => 'DA',
			'arae' => 400,
			'aras' => 180,
			'arask' => 'G',
			'description' => 'FV4202，性能中庸地位尴尬的中型坦克。',
			),
			9 => array
			(
			'probability' => 90,
			'mercsalary' => 150,
			'mercfireaction' => 1,	
			'club' => 2,
			'name' => '萨鲁法尔大王',
			'icon' => 159,
			'pose'=> 2,
			'gd' => 'm',
			'mhp' => 24000,
			'msp' => 2400,
			'att' => 800,
			'def' => 750,
			'lvl' => 40,
			'skill' => 800,
			'wep' => '高阶督军的斩首斧',
			'wepk' => 'WK',
			'wepe' => 1600,
			'weps' => 600,
			'wepsk' => 'Nnc',
			'arb' => '高阶督军的铠甲',
			'arbk' => 'DB',
			'arbe' => 100,
			'arbs' => 180,
			'arbsk' => 'ZAa',
			'arh' => '高阶督军的钢盔',
			'arhk' => 'DH',
			'arhe' => 500,
			'arhs' => 180,
			'arhsk' => 'D',
			'arf' => '高阶督军的战靴',
			'arfk' => 'DF',
			'arfe' => 500,
			'arfs' => 180,
			'arfsk' => '',
			'ara' => '高阶督军的护手',
			'arak' => 'DA',
			'arae' => 500,
			'aras' => 180,
			'arask' => '',
			'description' => '萨鲁法尔大王，部落的英雄，联盟的噩梦。',
			),
			10 => array
			(
			'probability' => 80,
			'mercsalary' => 150,
			'mercfireaction' => 1,	
			'club' => 14,
			'name' => 'Chuck Norris',
			'icon' => 160,
			'pose'=> 2,
			'gd' => 'm',
			'mhp' => 18000,
			'msp' => 2400,
			'att' => 1000,
			'def' => 1000,
			'lvl' => 40,
			'skill' => 800,
			'wep' => '阿姆斯特朗回旋踢',
			'wepk' => 'WP',
			'wepe' => 1900,
			'weps' => 600,
			'wepsk' => 'N',
			'arb' => '硬汉的躯体',
			'arbk' => 'DB',
			'arbe' => 200,
			'arbs' => 180,
			'arbsk' => 'ZAa',
			'arh' => '硬汉的气魄',
			'arhk' => 'DH',
			'arhe' => 700,
			'arhs' => 180,
			'arhsk' => 'D',
			'arf' => '硬汉的灵敏',
			'arfk' => 'DF',
			'arfe' => 700,
			'arfs' => 180,
			'arfsk' => '',
			'ara' => '硬汉的铁拳',
			'arak' => 'DA',
			'arae' => 700,
			'aras' => 180,
			'arask' => '',
			'description' => 'Chuck Norris，美国第一硬汉。其地位与春哥在中国的地位相似。',
			),
		),
	);
}

?>