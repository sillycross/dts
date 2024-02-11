<?php
namespace logistics
{
	//卡片售卖价格
	$cardtype_sellprice = array(
		'S' => 4000,
		'A' => 1800,
		'B' => 750,
		'C' => 200,
		'M' => 100,
	);
	
	//碎卡和闪卡售卖的价格倍率，比抽卡返还倍率稍高一点
	$card_sellprice_blink_rate = array(
		20 => 30,
		10 => 6,
	);
	
	//售卖物品列表，每个array包含名称、类别、价格、文字介绍、是否非卖品（1为非卖品）
	//类别1表示可使用道具（消耗品），2表示装饰品
	$logistics_shop_items = array(
		1 => array('切糕盒子',1,120,'使用后会随机获得一定量的切糕',0),
		2 => array('闪光贴膜',1,233,'使用后可消耗切糕，使一张卡片变为闪烁',0),
		3 => array('棱镜碎片',1,999,'使用后可消耗切糕，使一张卡片变为镜碎',0),
		4 => array('天马能量饮料',1,777,'使用后可选择一张卡片完成充能',0),
		5 => array('黄鸡玩偶',2,3000,'<span class="yellow b">“咕咕咕！”</span>',0),
		6 => array('棕色的Howling手办',2,7777,'<span class="brickred b">“银月哨兵是不死之身！”</span>',0),
		7 => array('深蓝色的S.A.S手办',2,7777,'<span class="blue b">“只要能为我的族人复仇，哪怕我堕入永劫地狱也在所不惜！”</span>',0),
		8 => array('天青色的Annabelle手办',2,7777,'<span class="lightblue b">“只要你相信神的存在，什么邪恶都没法左右你。”</span>',0),
		9 => array('红色的星海手办',2,7777,'<span class="darkred b">“你住酒店时有没有第一时间确认逃生通道的习惯？没有吧？我有。”</span>',0),
		10 => array('粉色的Sophia手办',2,7777,'<span class="ltpurple b">“今天的Sophia也是元气满满的哟！”</span>',0),
	);
	
	//类别1表示可使用道具（消耗品），2表示装饰品
	$logistics_itemtype = array(
		1 => '消耗品',
		2 => '装饰品',
	);
	
	//卡片镀闪或碎消耗的切糕量
	$cardblink_upgrade_cost = array(
		'S' => array('23333','157200'),
		'A' => array('4666','23333'),
		'B' => array('999','4666'),
		'C' => array('233'),
	);
	
}
?>