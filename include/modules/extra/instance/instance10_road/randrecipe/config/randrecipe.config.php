<?php

namespace randrecipe
{
	//随机配方素材的作用：'main'：主素材，'sub'：副素材，'itmsk'：提供属性的额外素材，'itme'：提供效果值的额外素材，'itms'：提供耐久值的额外素材
	
	//随机名称生成
	$randrecipe_resultname = array(
		'E_prefix' => array('神秘的','沉默的','毁灭的','黑暗的','荣耀的','诡异的','闪耀的','幽灵的','贪婪的','恐怖的','狡猾的','腐蚀的','机械的','平凡的','狂野的','幻觉的','无畏的'),
		'H_prefix' => array('美味的','恶臭的','香脆的','难以下咽的','闪耀的','柔滑的','清新的','浓郁的','热辣的','冰凉的','酸甜的','香辣的','温暖的','果味的'),
		'WP' => array('铁锤','皮鞭','手杖'),
		'WK' => array('大刀','长剑','利刃','刺枪'),
		'WG' => array('手枪','火铳','火箭炮'),
		'WC' => array('雪球','飞镖','卡牌'),
		'WD' => array('炸弹','爆弹','地雷'),
		'WF' => array('符卡','魔弹','节杖'),
		'DB' => array('盔甲','大衣','西装','战甲B'),
		'DH' => array('头盔','护目镜','风帽','战甲H'),
		'DA' => array('盾牌','利爪','手套','战甲A'),
		'DF' => array('跑鞋','皮靴','尾巴','战甲F'),
		'A' => array('挂件','项链','插件','饰品A'),
		'HH' => array('面包','伤药','压缩饼干'),
		'HS' => array('糊糊','伤药','混合物'),
		'HB' => array('秘药','伤药','罐头')
	);
	
	//合成产物的属性池
	$randrecipe_itmsk_list = Array
	(
		'W' => array('u','e','i','w','p','u','e','i','w','p','c','c','d'),
		'D' => array('P','K','C','G','D','F','U','E','I','W','q','M','a','A','c','H'),
		'A' => array('c','H','M','A','a'),
	);
	
	//主素材要求
	$main_stuff = array
	(
		'WP' => array('itmk'=>array('WP'), 'itm'=>array('拳','棍棒')),
		'WK' => array('itmk'=>array('WK'), 'itm'=>array('刀','剑','水果刀')),
		'WC' => array('itmk'=>array('WC'), 'itm'=>array('球','镖')),
		'WG' => array('itmk'=>array('WG'), 'itm'=>array('枪','炮','喷雾器罐')),
		'WD' => array('itmk'=>array('WD'), 'itm'=>array('打火机','信管','导火线','炸弹')),
		'WF' => array('itmk'=>array('WF'), 'itm'=>array('空白符卡','方块')),
		'DB' => array('itmk'=>array('DB'), 'itm'=>array('针线包','死库水')),
		'DH' => array('itmk'=>array('DH'), 'itm'=>array('针线包','帽')),
		'DA' => array('itmk'=>array('DA'), 'itm'=>array('针线包','手套')),
		'DF' => array('itmk'=>array('DF'), 'itm'=>array('针线包','鞋')),
		'A' => array('itmk'=>array('A'), 'itm'=>array('方块')),
		'HH' => array('itmk'=>array('HH','HS'), 'itm'=>array('面包','药')),
		'HS' => array('itmk'=>array('HH','HS'), 'itm'=>array('水','药')),
		'HB' => array('itmk'=>array('HH','HS','HB'), 'itm'=>array('面包','水','药'))
	);
	
	//副素材要求
	$sub_stuff = array
	(
		'WP' => array('itmk'=>array('WP','WK','WD'), 'itm'=>array('拳','棍棒','钉')),
		'WK' => array('itmk'=>array('WP','WK'), 'itm'=>array('刀','剑','磨刀石')),
		'WC' => array('itmk'=>array('WC'), 'itm'=>array('球','镖')),
		'WG' => array('itmk'=>array('WG'), 'itm'=>array('枪','炮','喷雾器罐','电子')),
		'WD' => array('itmk'=>array('WD','T'), 'itm'=>array('伏特加','信管','导火线','地雷')),
		'WF' => array('itmk'=>array('WF','V'), 'itm'=>array('方块','弹幕')),
		'DB' => array('itmk'=>array('DB'), 'itm'=>array('校服','死库水')),
		'DH' => array('itmk'=>array('DH'), 'itm'=>array('镜','帽')),
		'DA' => array('itmk'=>array('DA'), 'itm'=>array('装甲','手套')),
		'DF' => array('itmk'=>array('DF'), 'itm'=>array('裤','鞋')),
		'A' => array('itmk'=>array('A'), 'itm'=>array('方块')),
		'HH' => array('itmk'=>array('HH','HS'), 'itm'=>array('面包','水','药','果')),
		'HS' => array('itmk'=>array('HH','HS'), 'itm'=>array('水','药','果','酒')),
		'HB' => array('itmk'=>array('HH','HS','HB'), 'itm'=>array('面包','水','药','果'))
	);
	
		
	//属性素材要求
	$itmsk_stuff = array
	(
		'N' => array('itmsk'=>array('N'), 'itm'=>array('棍棒'), 'itmk'=>array('WD')),
		'n' => array('itmsk'=>array('n'), 'itm'=>array('针'), 'itmk'=>array('WK')),
		'y' => array('itmsk'=>array('y'), 'itmk'=>array('WF')),
		'r' => array('itmsk'=>array('r'), 'itmk'=>array('WG')),
		'u' => array('itmsk'=>array('u'), 'itm'=>array('火','油')),
		'i' => array('itmsk'=>array('i'), 'itm'=>array('冰')),
		'w' => array('itmsk'=>array('w'), 'itmk'=>array('弹')),
		'e' => array('itmsk'=>array('e'), 'itm'=>array('电')),
		'p' => array('itmsk'=>array('p'), 'itm'=>array('毒')),
		'd' => array('itmsk'=>array('d'), 'itmk'=>array('WD')),
		'P' => array('itm'=>array('针线包','方块')),
		'K' => array('itm'=>array('针线包','方块')),
		'C' => array('itm'=>array('针线包','方块')),
		'G' => array('itm'=>array('针线包','方块')),
		'F' => array('itm'=>array('针线包','方块')),
		'D' => array('itm'=>array('针线包','方块')),
		'A' => array('itmsk'=>array('A'), 'itm'=>array('宝石方块')),
		'B' => array('itmsk'=>array('B'), 'itm'=>array('悲叹之种')),
		'U' => array('itm'=>array('针线包','方块')),
		'E' => array('itm'=>array('针线包','方块')),
		'I' => array('itm'=>array('针线包','方块')),
		'W' => array('itm'=>array('针线包','方块')),
		'q' => array('itm'=>array('针线包','方块')),
		'a' => array('itmsk'=>array('a'), 'itm'=>array('宝石方块')),
		'b' => array('itmsk'=>array('b'), 'itm'=>array('悲叹之种')),
		'M' => array('itmsk'=>array('M'), 'itmk'=>array('DF')),
		'c' => array('itmsk'=>array('c'), 'itmk'=>array('A','DA')),
		'H' => array('itmsk'=>array('H'), 'itmk'=>array('DA')),
		'Z' => array('itmsk'=>array('Z'), 'itm'=>array('『祝福宝石』'))
	);
		
	//效果素材要求
	$itme_stuff = array
	(
		'WP' => array('itm'=>array('钉')),
		'WK' => array('itm'=>array('磨刀石')),
		'WC' => array('itmk'=>array('DA')),
		'WG' => array('itmk'=>array('GB')),
		'WD' => array('itmk'=>array('WD','TN')),
		'WF' => array('itmsk'=>array('d'),'itmk'=>array('WF','V')),
		'DB' => array('itm'=>array('针线包')),
		'DH' => array('itm'=>array('针线包')),
		'DA' => array('itm'=>array('针线包')),
		'DF' => array('itm'=>array('针线包')),
		'HH' => array('itm'=>array('锅','碗','药')),
		'HS' => array('itm'=>array('锅','碗','药')),
		'HB' => array('itm'=>array('锅','碗','药')),
		'common' => array('itmsk'=>array('Z'), 'itm'=>array('『祝福宝石』','增幅设备','魔导书'))
	);
		
	//耐久素材要求
	$itms_stuff = array
	(
		'WP' => array('itm'=>array('WP')),
		'WK' => array('itm'=>array('WK')),
		'WC' => array('itmk'=>array('WC')),
		'WG' => array('itmk'=>array('GB')),
		'WD' => array('itmk'=>array('WD','TN')),
		'WF' => array('itmsk'=>array('z'),'itmk'=>array('WF')),
		'DB' => array('itm'=>array('针线包')),
		'DH' => array('itm'=>array('针线包')),
		'DA' => array('itm'=>array('针线包')),
		'DF' => array('itm'=>array('针线包')),
		'HH' => array('itm'=>array('锅','碗','药')),
		'HS' => array('itm'=>array('锅','碗','药')),
		'HB' => array('itm'=>array('锅','碗','药')),
		'common' => array('itmsk'=>array('Z'), 'itm'=>array('『祝福宝石』'))
	);
	
}
?>