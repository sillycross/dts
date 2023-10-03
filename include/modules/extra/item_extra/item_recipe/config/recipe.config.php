<?php

namespace item_recipe
{
	//stuffa: 每个素材都要满足的条件
	//stuff1, stuff2, ..., stuff5: 5个素材分别满足的条件（配方自身不作为选项参与合成）
	//多个素材有单独条件的情况，按stuff1, stuff2, ...写，不能跳过
	//多个素材可能被同一个素材匹配的情况，包里靠前的道具会优先匹配stuff1，所以如果一个素材的条件完全包含另一个，那它要写到前面
	//但还是会有条件交叉的情况，这时候要手动交换包里道具的顺序；主要是如果要匹配更智能的话，判断那个函数写起来会很麻烦……呃啊
	//itm: 名字字符串，itm_match: 0:严格匹配，1:包含，2:去除前后缀
	//itmk: 类别字符串，itmk_match: 0:严格匹配，1:开始，2:结束（理论只用来判断游戏王星级 ）
	//itmsk: 子属性字符串（暂时只允许单个子属性），itmsk_match: 0:严格匹配，1:包含
	//if_consume: 是否消耗该素材
	//extra: 素材额外条件，'ygo'表示为游戏王道具，'edible'表示为回复道具，'weapon'表示为武器，'armor'表示为防具
	//如果定义了单个素材的条件，则该素材无视stuffa的条件
	//result: 合成结果数组
	//extra: 合成额外条件，'link':连接合成的link数，'materials':需要的合成材料数（具体数字或大于数字），'allow_repeat':是否允许重复, 'consume_recipe':是否消耗配方
	$recipe_mixinfo = array
	(
		1 => array
		(
			'stuffa' => array('extra'=>'ygo'),
			'result' => array('「刺刀枪管龙」LINK-4','WGK',300,77,'bArReL^l4'),
			'extra' => array('link'=>4, 'materials'=>'>2', 'consume_recipe'=>true,),
		),
		2 => array
		(
			'stuffa' => array('extra'=>'ygo'),
			'result' => array('「召命之神弓-阿波罗萨」LINK-4','WB',320,1,'rHA^l4'),
			'extra' => array('link'=>4, 'materials'=>'>1', 'allow_repeat'=>false, 'consume_recipe'=>true,),
		),
		3 => array
		(
			'stuffa' => array('itmk'=>'01','itmk_match'=>2,'extra'=>'ygo'),
			'result' => array('连接栗子球 LINK-1','A',30,1,'A^l1'),
			'extra' => array('link'=>1, 'materials'=>1, 'consume_recipe'=>true,),
		),
		4 => array
		(
			'stuffa' => array('extra'=>'edible'),
			'stuff1' => array('itm'=>'碗','itm_match'=>2,'if_consume'=>false),
			'result' => array('迷之炖菜','HB',240,60,),
			'extra' => array('materials'=>4, 'allow_repeat'=>true,),
		)
	);
}

?>
