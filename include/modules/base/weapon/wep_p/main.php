<?php

namespace wep_p
{
	function init() 
	{
		eval(import_module('weapon','itemmain'));
		
		//武器类型依赖的熟练度名称
		$skillinfo['P'] = 'wp';
		//武器类型攻击动词
		$attinfo['P'] = '殴打';
		//武器类型名
		$iteminfo['WP'] = '钝器';
		
		//基础反击率
		$counter_obbs['P'] = 65;
		//射程
		$rangeinfo['P'] = 3;
		//基础命中率
		$hitrate_obbs['P'] = 80;
		//各种攻击方式的最高命中率
		$hitrate_max_obbs['P'] = 90;
		//每点熟练增加的命中
		$hitrate_r['P'] = 0.025;
		//各种攻击方式的伤害变动范围，越少越稳定。
		$dmg_fluc['P'] = 15;
		//每点熟练度增加的伤害
		$skill_dmg['P'] = 0.6;
		//各种攻击方式的武器损伤概率
		$wepimprate['P'] = 12;
		//以该类武器击杀敌人后的死亡状态标号
		$wepdeathstate['P'] = 21;
	}
	
	function calculate_wepimp_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		$r=1;
		if ($pa['wep_kind']=='P' && $pa['weps']==$nosta) $r=2.5;	//无限耐久殴系武器损坏特判
		return $chprocess($pa, $pd, $active)*$r;
	}
		
	function get_attack_method(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (strpos($pdata['wepk'],'W')!==0) return 'P';//如果手里不是武器，当做钝器判断
		else return $chprocess($pdata);
	}
	
	function apply_weapon_imp(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('weapon','logger'));
		if ($pa['wep_kind']=='P' && $pa['weps']==$nosta)	//无限耐久殴系武器损坏特判
		{
			if (isset($pa['wepimp']) && $pa['wepimp'])
			{
				$pa['wepe']-=$pa['wepimp'];
				if ($active)
					$log .= "你的{$pa['wep']}的攻击力下降了{$pa['wepimp']}！<br>";
				else  $log .= "{$pa['name']}的{$pa['wep']}的攻击力下降了{$pa['wepimp']}！<br>";
				
				if ($pa['wepe']<=0) \weapon\weapon_break($pa, $pd, $active);
			}
		}
		else  $chprocess($pa,$pd,$active);
	}
}

?>
