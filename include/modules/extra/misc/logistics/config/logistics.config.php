<?php
namespace logistics
{
	//卡片售卖价格
	$cardtype_sellprice = array(
		'S' => 3200,
		'A' => 1600,
		'B' => 600,
		'C' => 200,
		'M' => 100,
	);
	
	//碎卡和闪卡售卖的价格倍率，比抽卡返还倍率稍高一点
	$card_sellprice_blink_rate = array(
		20 => 30,
		10 => 6,
	);
	
	//售卖的可使用物品，每个array包含名称、价格、文字介绍
	$logistics_shop_items = array(
		1 => array('切糕盒子',120,'消耗品。使用后会随机获得一定量的切糕'),
		2 => array('闪光贴膜',233,'消耗品。使用后可消耗切糕，使一张卡片变为闪烁或镜碎'),
	);
	
	//售卖的装饰品，每个array包含名称、价格、文字介绍
	$logistics_shop_accs = array(
		1 => array('黄鸡玩偶',3000,'咕咕咕！'),
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