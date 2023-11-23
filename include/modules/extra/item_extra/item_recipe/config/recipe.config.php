<?php

namespace item_recipe
{
	//stuff1, stuff2, ..., stuff5: 5个素材分别满足的条件（配方自身不作为选项参与合成）
	//stuffa: 未定义单独条件的素材要满足的条件
	//多个素材有单独条件的情况，按stuff1, stuff2, ...写，不能跳过
	//多个素材可能被同一个素材匹配的情况，包里靠前的道具会优先匹配stuff1，所以如果一个素材的条件完全包含另一个，那它要写到前面
	//但还是会有条件交叉的情况，这时候要手动交换包里道具的顺序；主要是如果要匹配更智能的话，判断那个函数写起来会很麻烦……呃啊
	//itm: 名字字符串，itm_match: 0:严格匹配，1:包含，2:去除前后缀
	//itmk: 类别字符串，itmk_match: 0:严格匹配，1:开始，2:结束（理论只用来判断游戏王星级）
	//itmsk: 子属性字符串（暂时只允许单个子属性），itmsk_match: 0:严格匹配，1:包含
	//if_consume: 是否消耗该素材，默认为true
	//extra: 素材额外条件，'ygo'表示为游戏王道具，'edible'表示为回复道具，'weapon'表示为武器，'armor'表示为防具	
	//result: 合成结果数组
	//extra: 合成额外条件，'link':连接合成的link数，'materials':需要的合成材料数（具体数字或大于数字），'allow_repeat':是否允许重复，默认为true， 'consume_recipe':是否消耗配方，默认为false
	$recipe_mixinfo = array
	(
		1 => array
		(
			'stuffa' => array('itmk'=>'01','itmk_match'=>2,'extra'=>'ygo'),
			'result' => array('连接栗子球 LINK-1','A',30,1,'A^l1'),
			'extra' => array('link'=>1, 'materials'=>1, 'consume_recipe'=>true,),
		),
		2 => array
		(
			'stuffa' => array('itmk'=>'01','itmk_match'=>2,'extra'=>'ygo'),
			'result' => array('「纳祭之魔·阿尼玛」-仮','R',1,1,'3'),
			'extra' => array('link'=>1, 'materials'=>1, 'consume_recipe'=>true,),
		),
		3 => array
		(
			'stuff1' => array('itm'=>'★连接认证1★','itm_match'=>0),
			'result' => array('「纳祭之魔·阿尼玛」LINK-1','A',30,1,'n^l1'),
			'extra' => array('materials'=>1, 'consume_recipe'=>true,),
		),
		4 => array
		(
			'stuff1' => array('itmsk'=>'s','itmsk_match'=>1,'extra'=>'ygo'),
			'stuff2' => array('extra'=>'ygo'),
			'result' => array('「水晶机巧-继承玻纤」LINK-2','DA',150,80,'Hc^l2'),
			'extra' => array('link'=>2, 'materials'=>2, 'consume_recipe'=>true,),
		),
		5 => array
		(
			'stuffa' => array('extra'=>'ygo'),
			'result' => array('「交织绵羊」LINK-2','A',70,1,'c^l2'),
			'extra' => array('link'=>2, 'materials'=>2, 'allow_repeat'=>false, 'consume_recipe'=>true,),
		),
		6 => array
		(
			'stuffa' => array('extra'=>'ygo'),
			'result' => array('「梦幻崩影·凤凰」-仮','R',1,1,'7'),
			'extra' => array('link'=>2, 'materials'=>2, 'allow_repeat'=>false, 'consume_recipe'=>true,),
		),
		7 => array
		(
			'stuff1' => array('itm'=>'★连接认证2★','itm_match'=>0),
			'result' => array('「梦幻崩影·凤凰」LINK-2','WC',190,'∞','ufdA^l2'),
			'extra' => array('materials'=>1, 'consume_recipe'=>true,),
		),
		8 => array
		(
			'stuffa' => array('itmsk'=>'^xyz', 'itmsk_match'=>1),
			'result' => array('「无限起动要塞 百万吨百臂狂风」-仮','R',1,1,'9'),
			'extra' => array('link'=>3, 'materials'=>3, 'consume_recipe'=>true,),
		),
		9 => array
		(
			'stuff1' => array('itm'=>'★连接认证3★','itm_match'=>0),
			'result' => array('「无限起动要塞 百万吨百臂狂风」LINK-3','DFS',400,400,'ARb^l3'),
			'extra' => array('materials'=>1, 'consume_recipe'=>true,),
		),
		10 => array
		(
			'stuffa' => array('extra'=>'ygo'),
			'result' => array('「三栅极男巫」-仮','R',1,1,'11'),
			'extra' => array('link'=>3, 'materials'=>'>1', 'consume_recipe'=>true,),
		),
		11 => array
		(
			'stuff1' => array('itm'=>'★连接认证3★','itm_match'=>0),
			'result' => array('「三栅极男巫」LINK-3','WC',220,'∞','rNnmZ^l3'),
			'extra' => array('materials'=>1, 'consume_recipe'=>true,),
		),
		12 => array
		(
			'stuffa' => array('extra'=>'ygo'),
			'result' => array('「梦幻崩影·独角兽」-仮','R',1,1,'13'),
			'extra' => array('link'=>3, 'materials'=>'>1', 'allow_repeat'=>false, 'consume_recipe'=>true,),
		),
		13 => array
		(
			'stuff1' => array('itm'=>'★连接认证3★','itm_match'=>0),
			'result' => array('「梦幻崩影·独角兽」LINK-3','DFS',220,220,'ewycM^l3'),
			'extra' => array('materials'=>1, 'consume_recipe'=>true,),
		),
		14 => array
		(
			'stuffa' => array('extra'=>'ygo'),
			'result' => array('「刺刀枪管龙」-仮','R',1,1,'15'),
			'extra' => array('link'=>4, 'materials'=>'>2', 'consume_recipe'=>true,),
		),		
		15 => array
		(
			'stuff1' => array('itm'=>'★连接认证4★','itm_match'=>0),
			'result' => array('「刺刀枪管龙」LINK-4','WGK',300,77,'bArReLZ^l4'),
			'extra' => array('materials'=>1, 'consume_recipe'=>true,),
		),	
		16 => array
		(
			'stuffa' => array('extra'=>'ygo'),
			'result' => array('「召命之神弓-阿波罗萨」-仮','R',1,1,'17'),
			'extra' => array('link'=>4, 'materials'=>'>1', 'allow_repeat'=>false, 'consume_recipe'=>true,),
		),
		17 => array
		(
			'stuff1' => array('itm'=>'★连接认证4★','itm_match'=>0),
			'result' => array('「召命之神弓-阿波罗萨」LINK-4','WB',320,1,'rkHA^l4'),
			'extra' => array('materials'=>1, 'consume_recipe'=>true,),
		),
		18 => array
		(
			'stuffa' => array('extra'=>'ygo'),
			'result' => array('「访问码语者」-仮','R',1,1,'19'),
			'extra' => array('link'=>4, 'materials'=>'>1', 'consume_recipe'=>true,),
		),
		19 => array
		(
			'stuff1' => array('itm'=>'★连接认证4★','itm_match'=>0),
			'result' => array('「访问码语者」LINK-4','WD',530,'∞','rdNy^ac1^l4'),
			'extra' => array('materials'=>1, 'consume_recipe'=>true,),
		),
		20 => array
		(
			'stuffa' => array('extra'=>'ygo'),
			'result' => array('「防火龙·暗流体」-仮','R',1,1,'21'),
			'extra' => array('link'=>5, 'materials'=>'>2', 'consume_recipe'=>true,),
		),
		21 => array
		(
			'stuff1' => array('itm'=>'★连接认证4★','itm_match'=>0),
			'stuff2' => array('itm'=>'★连接认证1★','itm_match'=>0),
			'result' => array('「防火龙·暗流体」LINK-5','WC',450,'∞','reNdbLZ^l5'),
			'extra' => array('materials'=>2, 'consume_recipe'=>true,),
		),
		22 => array
		(
			'stuffa' => array('extra'=>'ygo'),
			'result' => array('「闭锁世界的冥神」LINK-5','WC',300,'∞','iMb^l5'),
			'extra' => array('link'=>5, 'materials'=>'>3', 'consume_recipe'=>true,),
		),
		23 => array
		(
			'stuff1' => array('itmsk'=>'^xyz','itmsk_match'=>1),
			'stuff2' => array('itm'=>'☆叠♂放☆','itm_match'=>0),
			'result' => array('「天霆号 阿宙斯」☆12','WG',300,30,'uedZ^xyz12'),
			'extra' => array('materials'=>2, 'consume_recipe'=>true,),
		),
		24 => array
		(
			'stuff1' => array('extra'=>'ygo'),
			'stuff2' => array('itm'=>'☆叠♂放☆','itm_match'=>0),
			'result' => array('「灾厄之星 堤·丰」☆12','DBS',290,290,'aZ^xyz12'),
			'extra' => array('materials'=>2, 'consume_recipe'=>true,),
		),
		25 => array
		(
			'stuff1' => array('extra'=>'ygo'),
			'stuff2' => array('itm'=>'☆八星认证☆','itm_match'=>0),
			'result' => array('「海龟坏兽 加美西耶勒」★8','DFS08',220,300,'MaZ'),
			'extra' => array('materials'=>2, 'consume_recipe'=>true,),
		),
		26 => array
		(
			'stuffa' => array('extra'=>'ygo'),
			'result' => array('「守护者·奇美拉」★8','WC08',330,33,'uipwea'),
			'extra' => array('materials'=>3, 'allow_repeat'=>false, 'consume_recipe'=>true,),
		),
		27 => array
		(
			'stuff1' => array('itm'=>'☆八星认证☆','itm_match'=>0),
			'result' => array('「相剑大师-赤霄」★8','WK08',280,'∞','ufa^001'),
			'extra' => array('materials'=>1, 'consume_recipe'=>true,),
		),
		28 => array
		(
			'stuff1' => array('itm'=>'超重型炮塔列车 古斯塔夫最大炮 ☆10','itm_match'=>2),
			'stuff2' => array('itm'=>'☆叠♂放☆','itm_match'=>0),
			'result' => array('「超重型炮塔列车 破天巨爱」☆11','WJ',4000,40,'NnrdZ^xyz11'),
			'extra' => array('materials'=>2, 'consume_recipe'=>true,),
		),
		29 => array
		(
			'stuff1' => array('itmsk'=>'^001','itmsk_match'=>1),
			'stuff2' => array('itmsk'=>'^xyz','itmsk_match'=>1),
			'result' => array('「旧神 努茨」★4','WC04',250,'∞','d'),
			'extra' => array('materials'=>2, 'consume_recipe'=>true,),
		),
		30 => array
		(
			'stuff1' => array('itm'=>'☆十星认证☆','itm_match'=>0),
			'result' => array('「鲜花女男爵」★10','WC10',500,'∞','wdya^ac1^001'),
			'extra' => array('materials'=>1, 'consume_recipe'=>true,),
		),
		31 => array
		(
			'stuff1' => array('itm'=>'「珠泪哀歌族·水仙女人鱼」★5','itm_match'=>2),
			'stuff2' => array('itm'=>'珠泪哀歌族','itm_match'=>1),
			'stuff3' => array('itm'=>'☆八星认证☆','itm_match'=>0),
			'result' => array('「珠泪哀歌族·鲁莎卡人鱼」★8','WC08',300,'∞','rikAyZ'),
			'extra' => array('materials'=>3, 'consume_recipe'=>true,),
		),
		32 => array
		(
			'stuff1' => array('itm'=>'★连接认证2★','itm_match'=>0),
			'result' => array('「铁兽战线 块击之贝尔布鲁厄姆」LINK-2','WC',170,'∞','rcNj^l2'),
			'extra' => array('materials'=>1, 'consume_recipe'=>true,),
		),
		33 => array
		(
			'stuff1' => array('itm'=>'自奏圣乐','itm_match'=>1),
			'stuff2' => array('extra'=>'ygo'),
			'result' => array('自奏圣乐·伽拉忒亚 LINK-2','WC',180,120,'wA^sv1j^l2'),
			'extra' => array('link'=>2, 'materials'=>2, 'consume_recipe'=>true,),
		),
		//以上为游戏王相关配方合成
		50 => array
		(
			'stuff1' => array('itm'=>'碗','itm_match'=>2,'if_consume'=>false),
			'stuff2' => array('itm'=>'菇','itm_match'=>1),
			'stuff3' => array('itm'=>'菇','itm_match'=>1),
			'stuff4' => array('itm'=>'雏菊','itm_match'=>1),
			'result' => array('迷之炖菜','HB',320,60,),
			'extra' => array('materials'=>4, 'allow_repeat'=>true,),
		),
		51 => array
		(
			'stuff1' => array('itm'=>'游戏王卡包','itm_match'=>0),
			'stuff2' => array('itm'=>'《现代游戏王》','itm_match'=>0),
			'stuff3' => array('itm'=>'「失衡！不削！必亡！」','itm_match'=>0),
			'stuff4' => array('itm'=>'奇怪的按钮','itm_match'=>0),
			'result' => array('☆坐☆牢☆','WD',65535,'∞','FUCKkOnamItilExpLodeV^001'),
			'extra' => array('materials'=>4, 'consume_recipe'=>true,),
		),
		52 => array
		(
			'stuff1' => array('itm'=>'触手的力量','itm_match'=>0),
			'stuff2' => array('itm'=>'蘑菇','itm_match'=>1),
			'stuff3' => array('itm'=>'魔导书','itm_match'=>1),
			'result' => array('码符「终极BUG·拉电闸」','WF',1000,6,'r'),
			'extra' => array('materials'=>3, 'consume_recipe'=>true,),
		),
		//辉夜卡配方
		100 => array
		(
			'stuff1' => array('itm'=>'Untainted Glory','itm_match'=>0),
			'stuff2' => array('itm'=>'地板','itm_match'=>1),
			'result' => array('「英灵殿的一块天花板」','WP',333,33,'Nd'),
			'extra' => array('materials'=>2, 'consume_recipe'=>true,),
		),
		101 => array
		(
			'stuff1' => array('itm'=>'氢','itm_match'=>1),
			'stuff2' => array('itm'=>'K','itm_match'=>1),
			'stuff3' => array('itm'=>'气','itm_match'=>1),
			'stuff4' => array('itm'=>'电磁','itm_match'=>1),
			'result' => array('「常温超导材料」','EI',1,1,),
			'extra' => array('materials'=>4, 'consume_recipe'=>true,),
		),
		102 => array
		(
			'stuff1' => array('itm'=>'月光','itm_match'=>1),
			'stuff2' => array('itm'=>'岩石','itm_match'=>2),
			'stuff3' => array('itm'=>'车','itm_match'=>1),
			'result' => array('☆变色月壤☆','ME',33,2,'x'),
			'extra' => array('materials'=>3, 'consume_recipe'=>true,),
		),
		103 => array
		(
			'stuff1' => array('itm'=>'黑色方块','itm_match'=>0),
			'stuff2' => array('itmsk'=>'B','itmsk_match'=>1),
			'result' => array('★基岩★','WP',64,65535,'Bb'),
			'extra' => array('materials'=>2, 'consume_recipe'=>true,),
		),
		104 => array
		(
			'stuff1' => array('itm'=>'黄鸡','itm_match'=>1),
			'stuff2' => array('itm'=>'大衣','itm_match'=>1),
			'result' => array('☆黄鸡的皮衣☆','DB',133,333,'Aaz'),
			'extra' => array('materials'=>2, 'consume_recipe'=>true,),
		),
		105 => array
		(
			'stuff1' => array('itm'=>'卡牌包','itm_match'=>1),
			'stuff2' => array('itmsk'=>'^ac','itmsk_match'=>1,'extra'=>'weapon'),
			'result' => array('★混沌碎纸★','WC',199,4,'v'),
			'extra' => array('materials'=>2, 'consume_recipe'=>true,),
		),
		106 => array
		(
			'stuffa' => array('itmk'=>'WF','itmk_match'=>1,'itmsk'=>'e','itmsk_match'=>1),
			'result' => array('「宏电子」','WF',444,22,'e'),
			'extra' => array('materials'=>3, 'consume_recipe'=>true,),
		),
		107 => array
		(
			'stuff1' => array('itm'=>'盟军 次时代鸟人 ★3','itm_match'=>2),
			'stuff2' => array('itm'=>'星见兽 加里斯 ★3','itm_match'=>2),
			'stuff3' => array('itm'=>'核成恶魔 ★3','itm_match'=>2),
			'result' => array('『星见炮』','WC',200,'∞','rwt'),
			'extra' => array('materials'=>3, 'consume_recipe'=>true,),
		),
		108 => array
		(
			'stuff1' => array('itm'=>'空想','itm_match'=>1),
			'stuff2' => array('itm'=>'睡衣','itm_match'=>1),
			'stuff3' => array('itm'=>'梦','itm_match'=>1),
			'result' => array('☆AC大逃杀振兴计划☆','HR',233,3,'AcdtS'),
			'extra' => array('materials'=>3, 'consume_recipe'=>true,),
		),
	);
}

?>
