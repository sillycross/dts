<?php

namespace wep_c
{
	function init() 
	{
		eval(import_module('weapon','itemmain'));
		
		//武器类型依赖的熟练度名称
		$skillinfo['C'] = 'wc';
		//武器类型攻击动词
		$attinfo['C'] = '投掷';
		//武器类型名
		$iteminfo['WC'] = '投掷兵器';
//		$iteminfo['WC01'] = '投掷兵器';
//		$iteminfo['WC02'] = '投掷兵器';
//		$iteminfo['WC03'] = '投掷兵器';
//		$iteminfo['WC04'] = '投掷兵器';
//		$iteminfo['WC05'] = '投掷兵器';
//		$iteminfo['WC06'] = '投掷兵器';
//		$iteminfo['WC07'] = '投掷兵器';
//		$iteminfo['WC08'] = '投掷兵器';
//		$iteminfo['WC09'] = '投掷兵器';
//		$iteminfo['WC10'] = '投掷兵器';
//		$iteminfo['WC11'] = '投掷兵器';
//		$iteminfo['WC12'] = '投掷兵器';
		
		//基础反击率
		$counter_obbs['C'] = 35;
		//射程
		$rangeinfo['C'] = 5;
		//基础命中率
		$hitrate_obbs['C'] = 70;
		//各种攻击方式的最高命中率
		$hitrate_max_obbs['C'] = 96;
		//每点熟练增加的命中
		$hitrate_r['C'] = 0.25;
		//各种攻击方式的伤害变动范围，越少越稳定。
		$dmg_fluc['C'] = 5;
		//每点熟练度增加的伤害
		$skill_dmg['C'] = 0.35;
		//各种攻击方式的武器损伤概率
		$wepimprate['C'] = 10000;
		//以该类武器击杀敌人后的死亡状态标号
		$wepdeathstate['C'] = 24;
	}
}

?>
