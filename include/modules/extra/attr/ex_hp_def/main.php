<?php

namespace ex_hp_def
{
	function init()
	{
		eval(import_module('itemmain'));
		$itemspkinfo['H'] = '控噬';
		$itemspkdesc['H']='自己受到的反噬伤害-90%';
		$itemspkremark['H']='反噬伤害指对敌方伤害破1000时自己受到的伤害';
		
		$itemspkinfo['h'] = '控伤';
		$itemspkdesc['h']='受到总伤害超过1997时变为1997';
		$itemspkremark['h']='10%概率失效';
	}
	
	function calculate_hp_rev_dmg(&$pa, &$pd, $active)
	{
		//计算反噬伤害
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['dmg_dealt']<1000) return 0;
		$rate = 0.5;
		if ($pa['dmg_dealt']>=2000) $rate = 2.0/3.0;
		if ($pa['dmg_dealt']>=5000) $rate = 0.8;
		if (\attrbase\check_in_itmsk('H', \attrbase\get_ex_attack_array($pa, $pd, $active))) $rate *= 0.1;
		$damage = round($pa['hp']*$rate);
		if ($damage >= $pa['hp']) $damage = $pa['hp'] - 1;
		return $damage;
	}
	
	//伤害制御效果发生率
	function get_dmg_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 90;
	}
	
	function check_dmg_def_attr(&$pa, &$pd, $active)
	{
		//判定伤害制御
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		if (\attrbase\check_in_itmsk('h',\attrbase\get_ex_def_array($pa, $pd, $active)))
		{
			//$dmg_dice = rand(1950,2050);
			$dmg_dice = 1997;
			if ($pa['dmg_dealt'] > $dmg_dice)
			{
				$proc_rate = get_dmg_def_proc_rate($pa, $pd, $active);
				$dice = rand(0,99);
				if ($dice<$proc_rate)
				{
					if ($active)
						$log .= "在{$pd['name']}的装备的作用下，攻击伤害被限制到了<span class=\"yellow b\">$dmg_dice</span>点！<br>";
					else  $log .= "在你的装备的作用下，攻击伤害被限制到了<span class=\"yellow b\">$dmg_dice</span>点！<br>";
					$pa['dmg_dealt'] = $dmg_dice;
				}
				else
				{
					if ($active)
						$log .= "<span class=\"red b\">{$pd['name']}的装备没能发挥限制攻击伤害的效果！</span><br>";
					else  $log .= "<span class=\"red b\">你的装备没能发挥限制攻击伤害的效果！</span><br>";
				}
			}
		}
	}
	
	function apply_total_damage_modifier_limit(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		check_dmg_def_attr($pa, $pd, $active);
	}
				
	function player_damaged_enemy(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('logger'));
		
		$chprocess($pa, $pd, $active);
		
		//伤害输出结束，判定反噬与HP制御
		$damage = calculate_hp_rev_dmg($pa, $pd, $active);
		if ($damage > 0)
		{
			if ($active)
				$log .= "惨无人道的攻击对你自身造成了<span class=\"red b\">$damage</span>点<span class=\"red b\">反噬伤害！</span><br>";
			else  $log .= "惨无人道的攻击对{$pa['name']}自身造成了<span class=\"red b\">$damage</span>点<span class=\"red b\">反噬伤害！</span><br>";
			$pa['hp']-=$damage;
		}
	}
}

?>
