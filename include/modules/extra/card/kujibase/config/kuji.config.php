<?php
namespace kujibase{
	$kujicost = array(
		0 => 99,//单抽
		1 => 999,//11连
		2 => 333,//愚蠢高抽
		3 => 799,//8抽4，其中4张是特定卡池
	);
	$kujiobbs = array(
		0 => array (1, 6, 26),//分别是S A B卡概率，总概率100，剩下的是C和M
		1 => array (1, 6, 26),
		2 => array (5, 25, 100),
		3 => array (1, 6, 26),
	);
	$kujinum = array(
		0 => 1,
		1 => 11,
		2 => 1,
		3 => 8,
	);
	$kujinum_in_pack = Array(//如果指定卡池，$kujinum卡中有几张卡会来自该卡池。也作为是否允许指定卡池的判定
		0 => 0,
		1 => 0,
		2 => 0,
		3 => 4,
	);
}
?>