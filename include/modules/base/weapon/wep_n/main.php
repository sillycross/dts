<?php

namespace wep_n
{
	function init() 
	{
		eval(import_module('weapon','itemmain'));
		
		//武器类型依赖的熟练度名称
		$skillinfo['N'] = 'wp';
		//武器类型攻击动词
		$attinfo['N'] = '殴打';
		//武器类型名
		$iteminfo['WN'] = '空手';
		
		//基础反击率
		$counter_obbs['N'] = 60;
		//射程
		$rangeinfo['N'] = 3;
		//基础命中率
		$hitrate_obbs['N'] = 80;
		//各种攻击方式的最高命中率
		$hitrate_max_obbs['N'] = 90;
		//每点熟练增加的命中
		$hitrate_r['N'] = 0.025;
		//各种攻击方式的伤害变动范围，越少越稳定。
		$dmg_fluc['N'] = 15;
		//每点熟练度增加的伤害
		$skill_dmg['N'] = 0.6;
		//各种攻击方式的武器损伤概率
		$wepimprate['N'] = -1;
		//以该类武器击杀敌人后的死亡状态标号
		$wepdeathstate['N'] = 20;
		
	}
	
	function get_external_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		$ret = $chprocess($pa, $pd, $active);
		if ($pa['wep_kind']=='N')
			$ret += round($pa[$skillinfo['N']]*2/3);
		return $ret;
	}
}

?>
