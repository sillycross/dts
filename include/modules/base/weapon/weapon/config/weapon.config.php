<?php

namespace weapon
{
	//■ 空手武器 ■
	$nowep = '拳头';
	//■ 无限耐久度 ■
	$nosta = '∞';
	
	$skilltypeinfo = Array('wp' => '殴','wk' => '斩', 'wg' => '射', 'wc' => '投', 'wd' => '爆', 'wf' => '灵');
	//前者是攻击按钮用的，后者是$log里用的
	$attinfo = $attinfo2 = Array();
	$skillinfo = Array();
	
	$wep_equip_list=Array(
		'wep',
	);
	
	//基础反击率
	$counter_obbs =  Array();
	//各种攻击方式的射程，射程大者可以反击射程小者，此外射程为0则代表不能反击任何系但也不能被任何系反击
	$rangeinfo = Array(); 
	//各种攻击方式的基础命中率
	$hitrate_obbs = Array();
	//各种攻击方式的最高命中率
	$hitrate_max_obbs = Array();
	//熟练度对命中的影响，每点熟练增加的命中，可以考虑区分武器
	$hitrate_r =  Array();
	//各种攻击方式的伤害变动范围，越少越稳定。
	$dmg_fluc = Array();
	//熟练度对伤害的影响，每点熟练度增加的伤害
	$skill_dmg = Array();
	//各种攻击方式的武器损伤概率，10000代表消耗性武器
	$wepimprate = Array();
	//以该类武器击杀敌人后的死亡状态标号
	$wepdeathstate = Array();
}

?>
