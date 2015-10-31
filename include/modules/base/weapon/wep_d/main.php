<?php

namespace wep_d
{
	function init() 
	{
		eval(import_module('weapon','itemmain'));
		
		//武器类型依赖的熟练度名称
		$skillinfo['D'] = 'wd';
		//武器类型攻击动词
		$attinfo['D'] = '伏击';
		//武器类型名
		$iteminfo['WD'] = '爆炸物';
		
		//基础反击率
		$counter_obbs['D'] = 0;
		//射程
		$rangeinfo['D'] = 0;
		//基础命中率
		$hitrate_obbs['D'] = 60;
		//各种攻击方式的最高命中率
		$hitrate_max_obbs['D'] = 70;
		//每点熟练增加的命中
		$hitrate_r['D'] = 0.02;
		//各种攻击方式的伤害变动范围，越少越稳定。
		$dmg_fluc['D'] = 25;
		//每点熟练度增加的伤害
		$skill_dmg['D'] = 0.75;
		//各种攻击方式的武器损伤概率
		$wepimprate['D'] = 10000;
		//以该类武器击杀敌人后的死亡状态标号
		$wepdeathstate['D'] = 25;
	}
}

?>
