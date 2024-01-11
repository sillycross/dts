<?php

namespace randrecipe
{
	//随机配方素材的作用：'main'：主素材，'sub'：副素材，'itmsk'：提供属性的额外素材，'itme'：提供效果值的额外素材，'itms'：提供耐久值的额外素材
	
	//随机名称生成
	$randrecipe_resultname = array(
		'rand_prefix' => array('光辉的','神圣的','不朽的','巨大的','奥术的','古老的','魔力的','自然的','迷人的','罕见的'),
		'E_prefix' => array('神秘','沉默','毁灭','黑暗','荣耀','诡异','闪耀','幽灵','贪婪','恐怖','狡猾','腐蚀','机械','平凡','狂野','幻觉','无畏'),
		'H_prefix' => array('美味','恶臭','香脆','难以下咽','闪耀','柔滑','清新','浓郁','热辣','冰凉','酸甜','香辣','温暖','果味'),
		'WP' => array('铁锤','皮鞭','手杖'),
		'WK' => array('大刀','长剑','利刃','刺枪'),
		'WG' => array('手枪','火铳','火箭炮'),
		'WC' => array('雪球','飞镖','卡牌'),
		'WD' => array('炸弹','爆弹','地雷'),
		'WF' => array('符卡','魔弹','节杖'),
		'WB' => array('猎弓','角弓','箭雨'),
		'WJ' => array('巨炮','狙击枪','重枪'),	
		'DB' => array('盔甲','大衣','西装','战甲B'),
		'DH' => array('头盔','护目镜','风帽','战甲H'),
		'DA' => array('盾牌','利爪','手套','战甲A'),
		'DF' => array('跑鞋','皮靴','尾巴','战甲F'),
		'A' => array('挂件','项链','插件','饰品A'),
		'HH' => array('面包','伤药','压缩饼干'),
		'HS' => array('糊糊','伤药','混合物'),
		'HB' => array('秘药','伤药','罐头')
	);
	
	//合成产物的基础属性池
	$randrecipe_itmsk_list = Array
	(
		'W' => array('u','e','i','w','p','u','e','i','w','p','c','c','d','N'),
		'D' => array('P','K','C','G','D','F','U','E','I','W','q','M','a','A','c','H'),
		'A' => array('c','H','M','A','a'),
	);
	
	//合成产物的素材提供属性池
	$randrecipe_stuff_itmsk_list = Array
	(
		'W' => array('u','e','i','w','p','u','c','d','N','n','r'),
		'D' => array('P','K','C','G','D','F','U','E','I','W','q','M','a','A','c','H','B','b'),
		'A' => array('c','H','M','A','a','B','b'),
	);
	
	//合成产物的奖励属性池，效果值达到键名的阈值后有概率额外抽取
	$randrecipe_bonus_itmsk_list = Array
	(
		'W' => array(500=>array('d','n','y','L'), 1000=>array('f','k','t','^ac1','Z'), 3000=>array('v','B','b'), 100000=>array('V')),
		'D' => array(1000=>array('B','b','^wc1'), 3000=>array('h','Z')),
		'H' => array(300=>array('Z')),//会有人强化补给？
	);
	
	//主素材要求
	//x为数字，'+x'表示增加效果与耐久值x点，'-x'表示减少效果与耐久值x点，'*x'表示效果与耐久值变为x倍
	$main_stuff = array
	(
		'WP' => array('itmk'=>array('WP'=>''), 'itm'=>array('拳'=>'*2','棍棒'=>'+30')),
		'WK' => array('itmk'=>array('WK'=>''), 'itm'=>array('刀'=>'*1.5','剑'=>'*2','水果刀'=>'+30')),
		'WC' => array('itmk'=>array('WC'=>''), 'itm'=>array('球'=>'+30','镖'=>'+90')),
		'WG' => array('itmk'=>array('WG'=>''), 'itm'=>array('枪'=>'+20','炮'=>'+50','喷雾器罐'=>'+30')),
		'WD' => array('itmk'=>array('WD'=>''), 'itm'=>array('打火机'=>'+20','信管'=>'+30','导火线'=>'+30','炸弹'=>'+50')),
		'WF' => array('itmk'=>array('WF'=>''), 'itm'=>array('空白符卡'=>'','方块'=>'+25')),
		'DB' => array('itmk'=>array('DB'=>''), 'itm'=>array('针线包'=>'+5','死库水'=>'+5')),
		'DH' => array('itmk'=>array('DH'=>''), 'itm'=>array('针线包'=>'+5','帽'=>'+30')),
		'DA' => array('itmk'=>array('DA'=>''), 'itm'=>array('针线包'=>'+5','手套'=>'+30')),
		'DF' => array('itmk'=>array('DF'=>''), 'itm'=>array('针线包'=>'+5','鞋'=>'+30')),
		'A' => array('itmk'=>array('A'=>''), 'itm'=>array('方块'=>'')),
		'HH' => array('itmk'=>array('HH'=>'','HS'=>''), 'itm'=>array('面包'=>'+5','药'=>'*1.5')),
		'HS' => array('itmk'=>array('HH'=>'','HS'=>''), 'itm'=>array('水'=>'+5','药'=>'*1.5')),
		'HB' => array('itmk'=>array('HH'=>'','HS'=>'','HB'=>''), 'itm'=>array('面包'=>'+5','水'=>'+5','药'=>'*1.5'))
	);
	
	//副素材要求
	//x为数字，'+x'表示增加效果与耐久值x点，'-x'表示减少效果与耐久值x点，'*x'表示效果与耐久值变为x倍
	$sub_stuff = array
	(
		'WP' => array('itmk'=>array('WP'=>'+20','WK'=>'+20','WD'=>'+20'), 'itm'=>array('拳'=>'*2','棍棒'=>'+30','钉'=>'+10')),
		'WK' => array('itmk'=>array('WP'=>'+20','WK'=>'+20'), 'itm'=>array('刀'=>'*1.5','剑'=>'*2','磨刀石'=>'+10')),
		'WC' => array('itmk'=>array('WC'=>'+20','ygo'=>'+30'), 'itm'=>array('球'=>'+40','镖'=>'+80')),
		'WG' => array('itmk'=>array('WG'=>'+20'), 'itm'=>array('枪'=>'+20','炮'=>'+30','喷雾器罐'=>'+30','电子'=>'+30')),
		'WD' => array('itmk'=>array('WD'=>'+20','T'=>'+20'), 'itm'=>array('伏特加'=>'+20','信管'=>'+30','导火线'=>'+30','地雷'=>'+10')),
		'WF' => array('itmk'=>array('WF'=>'+20','V'=>'+50'), 'itm'=>array('方块'=>'+30','弹幕'=>'+20')),
		'DB' => array('itmk'=>array('DB'=>'+20'), 'itm'=>array('校服'=>'+50','死库水'=>'+10')),
		'DH' => array('itmk'=>array('DH'=>'+20'), 'itm'=>array('镜'=>'+50','帽'=>'+20')),
		'DA' => array('itmk'=>array('DA'=>'+20'), 'itm'=>array('装甲'=>'+20','手套'=>'+20')),
		'DF' => array('itmk'=>array('DF'=>'+20'), 'itm'=>array('裤'=>'+20','鞋'=>'+20')),
		'A' => array('itmk'=>array('A'=>''), 'itm'=>array('方块'=>'')),
		'HH' => array('itmk'=>array('HH'=>'+20','HS'=>'+20'), 'itm'=>array('面包'=>'+5','水'=>'+5','药'=>'+10','果'=>'+10')),
		'HS' => array('itmk'=>array('HH'=>'+20','HS'=>'+20'), 'itm'=>array('水'=>'+5','药'=>'+5','果'=>'+10','酒'=>'+10')),
		'HB' => array('itmk'=>array('HH'=>'+20','HS'=>'+20','HB'=>'+30'), 'itm'=>array('面包'=>'+5','水'=>'+5','药'=>'+10','果'=>'+10','酒'=>'+15'))
	);
		
	//属性素材要求，单纯的提供属性
	$itmsk_stuff = array
	(
		'N' => array('itmsk'=>array('N'), 'itm'=>array('棍棒'), 'itmk'=>array('WD','WP')),
		'n' => array('itmsk'=>array('n'), 'itm'=>array('针'), 'itmk'=>array('WK','ER')),
		'y' => array('itmsk'=>array('y'), 'itmk'=>array('WF','GA')),
		'r' => array('itmsk'=>array('r'), 'itmk'=>array('WG')),
		'u' => array('itmsk'=>array('u'), 'itm'=>array('火','油')),
		'i' => array('itmsk'=>array('i'), 'itm'=>array('冰')),
		'w' => array('itmsk'=>array('w'), 'itmk'=>array('弹')),
		'e' => array('itmk'=>array('B','EE'), 'itmsk'=>array('e'), 'itm'=>array('电')),
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
		'b' => array('itmsk'=>array('b'), 'itm'=>array('悲叹之种'),'itmk'=>array('ss')),
		'M' => array('itmsk'=>array('M'), 'itmk'=>array('DF','EE','ER','U')),
		'c' => array('itmsk'=>array('c'), 'itmk'=>array('GA','DA')),
		'H' => array('itmsk'=>array('H'), 'itmk'=>array('DA','HM')),
		'Z' => array('itmsk'=>array('Z'), 'itm'=>array('『祝福宝石』'))
	);
		
	//效果素材要求
	//x为数字，'+x'表示增加效果值x点，'-x'表示减少效果值x点，'*x'表示效果值变为x倍，'u'表示类别强化（射变重枪，投变弓）
	$itme_stuff = array
	(
		'WP' => array('itm'=>array('钉'=>'+20'),'itmsk'=>array('N'=>'*1.3')),
		'WK' => array('itm'=>array('磨刀石'=>'+20')),
		'WC' => array('itmk'=>array('DA'=>'+20','ER'=>'u')),
		'WG' => array('itmk'=>array('GB'=>'+20','ER'=>'u')),
		'WD' => array('itmk'=>array('HR'=>'+70','TN'=>'+20')),
		'WF' => array('itmk'=>array('VF'=>'+80','V'=>'+40'),'itmsk'=>array('d'=>'+20')),
		'DB' => array('itm'=>array('针线包'=>'+10')),
		'DH' => array('itmk'=>array('ER'=>'+30'),'itm'=>array('针线包'=>'+10')),
		'DA' => array('itm'=>array('针线包'=>'+10')),
		'DF' => array('itm'=>array('针线包'=>'+10')),
		'HH' => array('itmk'=>array('M'=>'+50','MH'=>'+100','C'=>'+5'),'itm'=>array('锅'=>'+50','碗'=>'+30','药'=>'+20')),
		'HS' => array('itmk'=>array('M'=>'+50','MS'=>'+100','C'=>'+5'),'itm'=>array('锅'=>'+50','碗'=>'+30','药'=>'+20')),
		'HB' => array('itmk'=>array('M'=>'+50','MH'=>'+100','MS'=>'+100','C'=>'+5'),'itm'=>array('锅'=>'+50','碗'=>'+30','药'=>'+20')),
		'common' => array('itmsk'=>array('Z'=>'+50'), 'itm'=>array('『祝福宝石』'=>'*1.5','增幅设备'=>'*1.2','魔导书'=>'*1.5'))
	);
		
	//耐久素材要求
	//x为数字，'+x'表示增加耐久值x点，'-x'表示减少耐久值x点，'*x'表示耐久值变为x倍，'i'表示耐久变为无限
	$itms_stuff = array
	(
		'WP' => array('itmk'=>array('B'=>'+20')),
		'WK' => array('itm'=>array('WK'=>'+20')),
		'WC' => array('itmk'=>array('GA'=>'+20','ygo'=>'+30')),
		'WG' => array('itmk'=>array('GB'=>'+20')),
		'WD' => array('itmk'=>array('B'=>'+10','TN'=>'+10')),
		'WF' => array('itmk'=>array('V'=>'+20'),'itmsk'=>array('z'=>'+20')),
		'DB' => array('itm'=>array('针线包'=>'+10')),
		'DH' => array('itm'=>array('针线包'=>'+10')),
		'DA' => array('itm'=>array('针线包'=>'+10')),
		'DF' => array('itm'=>array('针线包'=>'+10')),
		'HH' => array('itmk'=>array('M'=>'+30','MH'=>'+60','C'=>'+5'),'itm'=>array('锅'=>'+50','碗'=>'+30','药'=>'+20')),
		'HS' => array('itmk'=>array('M'=>'+30','MS'=>'+60','C'=>'+5'),'itm'=>array('锅'=>'+50','碗'=>'+30','药'=>'+20')),
		'HB' => array('itmk'=>array('M'=>'+30','MH'=>'+50','MS'=>'+50','C'=>'+5'),'itm'=>array('锅'=>'+50','碗'=>'+30','药'=>'+20')),
		'common' => array('itmsk'=>array('Z'=>'+30'), 'itm'=>array('方块'=>'+30','『祝福宝石』'=>'*1.5'))
	);
	
}
?>