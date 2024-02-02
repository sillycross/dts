<?php

namespace ex_rapid_attr
{
	function init()
	{
		eval(import_module('itemmain'));
		$itemspkinfo['r'] = '连击';
		$itemspkdesc['r']='根据熟练度，进行2-6次连续攻击';
		$itemspkremark['r']='武器对应系每有200熟练度则连击次数+1；<br>第2次以后的连击伤害有折减（6次全中相当于单次攻击的4.6倍）';
	}
	
	function get_rapid_times($pa, $pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//计算连击次数
		return 2 + min ( floor($pa['fin_skill'] / 200), 4 );
	}

	//武器损伤增加
	function get_rapid_wepimp_increase(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1.35;
	}
	
	//武器损伤率随连击次数增加
	function calculate_wepimp_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$z = get_rapid_wepimp_increase($pa, $pd, $active);
		$r = $chprocess($pa, $pd, $active);
		for ($i=1; $i<$pa['actual_rapid_time']; $i++) $r*=$z;
		return $r;
	}
	
	//致伤率衰减
	function get_rapid_inf_rate_loss(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 0.9;
	}
	
	//致伤率随连击次数降低
	function calculate_inf_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$z = get_rapid_inf_rate_loss($pa, $pd, $active);
		$r = $chprocess($pa, $pd, $active);
		for ($i=1; $i<$pa['actual_rapid_time']; $i++) $r*=$z;
		return $r;
	}
	
	function get_rapid_damage_modifier(&$pa, &$pd, $active, $hit_time)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//连击伤害修正
		$z = Array( 0 => 0, 1 => 1, 2 => 2, 3 => 2.8, 4 => 3.4, 5 => 4, 6 => 4.6 );
		return $z[$hit_time];
	}
	
	//连击次数伤害加成提示
	function get_rapid_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = Array();
		if ($pa['is_rapid_strike'] == 1)
		{
			eval(import_module('logger'));
			if($pa['actual_hit_time'] > 1) {
				$log .= "{$pa['actual_rapid_time']}次连续攻击命中<span class=\"yellow b\">{$pa['actual_hit_time']}</span>次！";
			}
			$r = Array(get_rapid_damage_modifier($pa, $pd, $active, $pa['actual_hit_time']));
		}
		return $r;
	}
	
	//连击改到物伤加成阶段
	function get_primary_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = get_rapid_dmg_multiplier($pa, $pd, $active);
		return array_merge($r,$chprocess($pa, $pd, $active));
	}
	
	//连击命中率衰减
	function get_rapid_accuracy_loss(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 0.8;
	}
	
	function check_rapid(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=\attrbase\get_ex_attack_array($pa, $pd, $active);
		return $r;
	}
	
	function weapon_strike(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\attrbase\check_in_itmsk('r',check_rapid($pa, $pd, $active)))
		{
			$chprocess($pa, $pd, $active);
			return;
		}
		//开始连击属性特效
		eval(import_module('weapon','ex_rapid_attr','logger'));
		$pa['is_rapid_strike'] = 1;
		$rapid_times = get_rapid_times($pa, $pd, $active);
		$pa['actual_rapid_time'] = 0; $pa['actual_hit_time'] = 0; $is_hit = 0;
		for ($cur_rapid_time=0; $cur_rapid_time<$rapid_times; $cur_rapid_time++)
		{
			$pa['actual_rapid_time']++;
			$dice=rand(0,99);
			if ($dice<$pa['fin_hitrate'])	
			{
				$is_hit = 1;
				$pa['actual_hit_time']++;
				\weapon\post_weapon_strike_events($pa, $pd, $active, 1);
			}
			else
			{
				\weapon\post_weapon_strike_events($pa, $pd, $active, 0);
			}
			if ($pa['weps']!=$nosta && $pa['wepimp']>=$pa['weps']) break; 	//武器损坏时停止连击
			$pa['fin_hitrate']*=get_rapid_accuracy_loss($pa, $pd, $active);	//命中率随连击次数降低
		}
		
		$pa['is_hit']=$is_hit;
		if ($is_hit)
		{
			//计算物理伤害
			\weapon\calculate_physical_dmg($pa, $pd, $active);
		}
		else
		{
			$log .= "但是没有击中！<br>";
		}
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['is_rapid_strike'] = 0;
		$chprocess($pa, $pd, $active);
	}
}

?>
