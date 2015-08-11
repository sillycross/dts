<?php

namespace weapon
{
	function init() 
	{
		eval(import_module('player'));
		global $wep_equip_list;
		$equip_list=array_merge($equip_list,$wep_equip_list);
		$battle_equip_list=array_merge($battle_equip_list,$wep_equip_list);
	}
	
	function get_att_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1.0;
	}
	
	function get_internal_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		return $pa['att'];
	}
	
	function get_external_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		return $pa['wepe']*2;		//维持奇葩的老设定，实际计算效果是面板数值*2
	}
	
	function get_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return get_internal_att($pa,$pd,$active)+get_external_att($pa,$pd,$active);
	}
	
	function get_def_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1.0;
	}
	
	function get_internal_def(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pd['def'];
	}
	
	function get_def(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pd['internal_def'] = get_internal_def($pa,$pd,$active);
		return $pd['internal_def'];
	}
	
	function get_skill(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		return $pa[$skillinfo[$pa['wep_kind']]];
	}
	
	function get_attack_method(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return substr($pdata['wepk'],1,1);
	}
	
	function check_attack_method(&$pdata, $wm)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return get_attack_method($pdata)==$wm;
	}
	
	function load_user_combat_command(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('input'));
		if (check_attack_method($pdata,$command))
			$pdata['wep_kind']=$command;
		else  $pdata['wep_kind']=get_attack_method($pdata);
		$chprocess($pdata);
	}
	
	function load_auto_combat_command(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pdata['wep_kind']=get_attack_method($pdata);
		$chprocess($pdata);
	}
	
	function get_hitrate(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('weapon'));
		$hitrate = $hitrate_obbs[$pa['wep_kind']];
		$hitrate += round($pa['fin_skill'] * $hitrate_r[$pa['wep_kind']]); 
		$hitrate = min($hitrate, $hitrate_max_obbs[$pa['wep_kind']]);
		return $hitrate;
	}
	
	//基础伤害
	function get_primary_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		$pa['fin_att']=get_att($pa,$pd,$active)*get_att_multiplier($pa,$pd,$active);
		$pd['fin_def']=get_def($pa,$pd,$active)*get_def_multiplier($pa,$pd,$active);
		$att_pow=$pa['fin_att']; $def_pow=$pd['fin_def']; $ws=$pa['fin_skill']; $wp_kind=$pa['wep_kind'];
		$damage = ($att_pow/$def_pow)*$ws*$skill_dmg[$wp_kind];
		$fluc = rand(get_1st_dmg_factor_l($pa,$pd,$active,$dmg_fluc[$wp_kind]),get_1st_dmg_factor_h($pa,$pd,$active,$dmg_fluc[$wp_kind]));
		$dmg_factor = (100+$fluc)/100;
		$damage = round ( $damage * $dmg_factor * rand ( 4, 10 ) / 10 );
		if ($damage<1) $damage=1;
		return $damage;
	}
	
	//获取一次浮动
	function get_1st_dmg_factor_l(&$pa,&$pd,$active,$basefluc){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return -$basefluc;
	}
	
	function get_1st_dmg_factor_h(&$pa,&$pd,$active,$basefluc){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $basefluc;
	}
	
	//基础伤害修正系数
	function get_primary_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1.0;
	}
	
	//固定伤害
	function get_fixed_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 0;
	}
	
	//固定伤害修正系数
	function get_fixed_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1.0;
	}
	
	//物理伤害
	function get_physical_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$primary_dmg=get_primary_dmg($pa, $pd, $active) * get_primary_dmg_multiplier($pa, $pd, $active);
		$fixed_dmg=get_fixed_dmg($pa, $pd, $active);
		if ($fixed_dmg>0) $fixed_dmg *= get_fixed_dmg_multiplier($pa, $pd, $active);
		return round($primary_dmg + $fixed_dmg);
	}
	
	//物理伤害修正系数
	//注意由于物理伤害修正系数会在log里显示出来，所以决定这里返回一个数组，代表各次修正，最后结果是所有元素的乘积
	//请注意array_merge的次序，应该把你的结果放在array的最前面，这样各次修正数值出现次序才是和判定log的出现次序一致的
	//即，应该写return array_merge(Array(数值),$chprocess($pa,$pd,$active));而不是反过来
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return Array();
	}
	
	function calculate_physical_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		
		$multiplier = get_physical_dmg_multiplier($pa, $pd, $active);
		$dmg = get_physical_dmg($pa, $pd, $active);
		
		$fin_dmg=$dmg; $mult_words='';
		foreach ($multiplier as $key)
		{
			$fin_dmg=$fin_dmg*$key;
			$mult_words.="×{$key}";
		}
		$fin_dmg=round($fin_dmg);
		if ($fin_dmg < 1) $fin_dmg = 1;
		if ($mult_words=='')
			$log .= "造成<span class=\"red\">{$dmg}</span>点伤害！<br>";
		else  $log .= "造成{$dmg}{$mult_words}＝<span class=\"red\">{$fin_dmg}</span>点伤害！<br>";
		$pa['physical_dmg_dealt']+=$fin_dmg;
		$pa['dmg_dealt']+=$fin_dmg;
	}
	
	function calculate_wepimp_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		return $wepimprate[$pa['wep_kind']];
	}
	
	function calculate_wepimp(&$pa, &$pd, $active, $is_hit)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		$z=calculate_wepimp_rate($pa, $pd, $active);
		if (!$is_hit && $z<1000) return;	//没有击中，且非消耗性武器，不会损失耐久
		$dice=rand(0,99);
		if ($dice<$z) $pa['wepimp']++;
	}
	
	//武器伤害计算后事件
	//Q: 为什么要写这么一个脑残的函数？不能和计算伤害写到一起么？
	//A: 问那个设计连击的人去，不这么搞没法实现目前版本连击（只计算一次伤害，但武器损伤等特效发动多次）
	function post_weapon_strike_events(&$pa, &$pd, $active, $is_hit)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//判定武器损伤
		calculate_wepimp($pa, $pd, $active, $is_hit);
	}
	
	//这个函数是完整的一次武器攻击函数
	//武器攻击后需要做的事情请要么接管post_weapon_strike_events()，要么接管strike()，不要接管weapon_strike()本体
	function weapon_strike(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$dice=rand(0,99);
		if ($dice<$pa['fin_hitrate'])	
		{
			$is_hit = 1;
			//计算物理伤害
			calculate_physical_dmg($pa, $pd, $active);
		}
		else  
		{	
			$is_hit = 0;
			$log .= "但是没有击中！<br>";
		}
		$pa['is_hit'] = $is_hit;
		post_weapon_strike_events($pa, $pd, $active, $is_hit);
	}
	
	function strike(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		weapon_strike($pa,$pd,$active);
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('weapon','logger'));
		if ($active)
		{
			$log .= "使用{$pa['wep']}<span class=\"yellow\">".$attinfo[$pa['wep_kind']]."</span>{$pd['name']}！<br>";
		}
		else  
		{
			$log .= "{$pa['name']}使用{$pa['wep']}<span class=\"yellow\">".$attinfo[$pa['wep_kind']]."</span>你！<br>";
		}
		
		$pd['deathmark']=$wepdeathstate[$pa['wep_kind']];
		$pa['attackwith']=$pa['wep'];
		$pa['fin_skill']=get_skill($pa,$pd,$active);
		$pa['fin_hitrate']=get_hitrate($pa,$pd,$active);
		
		$chprocess($pa, $pd, $active);
	}
	
	function weapon_break(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('weapon','logger'));
		if ($active)
			if ($wepimprate[$pa['wep_kind']]<1000)
				$log .= "你的<span class=\"red\">{$pa['wep']}</span>使用过度，已经损坏，无法再装备了！<br>";
			else  $log .= "你的<span class=\"red\">{$pa['wep']}</span>用光了！<br>";
		else  if ($wepimprate[$pa['wep_kind']]<1000)
				$log .= "{$pa['name']}的<span class=\"red\">{$pa['wep']}</span>使用过度，已经损坏，无法再装备了！<br>";
			else  $log .= "{$pa['name']}的<span class=\"red\">{$pa['wep']}</span>用光了！<br>";
			
		$pa['wep'] = '拳头';
		$pa['wepk'] = 'WN';
		$pa['wepe']= 0;
		$pa['weps']= $nosta;
		$pa['wepsk']= '';
	}
	
	function apply_weapon_imp(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('weapon','logger'));
		if (isset($pa['wepimp']) && $pa['weps']!=$nosta)
		{
			$pa['weps']-=$pa['wepimp'];
			if ($active)
				if ($wepimprate[$pa['wep_kind']]<1000)
					$log .= "你的{$pa['wep']}的耐久度下降了{$pa['wepimp']}！<br>";
				else  $log .= "你用掉了{$pa['wepimp']}个{$pa['wep']}。<br>";
			else  if ($wepimprate[$pa['wep_kind']]<1000)
					$log .= "{$pa['name']}的{$pa['wep']}的耐久度下降了{$pa['wepimp']}！<br>";
				else  $log .= "{$pa['name']}用掉了{$pa['wepimp']}个{$pa['wep']}。<br>";
			
			if ($pa['weps']<=0) weapon_break($pa, $pd, $active);
		}
	}
	
	//计算武器熟练获得
	function calculate_attack_weapon_skill_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//增加熟练
	function apply_weapon_skill_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('weapon'));
		$pa[$skillinfo[$pa['wep_kind']]]+=calculate_attack_weapon_skill_gain($pa, $pd, $active);
	}
	
	//计算攻击经验获得
	function calculate_attack_exp_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$expup = round ( ($pd['lvl'] - $pa['lvl']) / 3 );
		$expup = $expup > 0 ? $expup : 1;
		return $expup;
	}
	
	//获取攻击经验
	function apply_attack_exp_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('lvlctl'));
		if ($pa['physical_dmg_dealt'] > 0) //有伤害才获得经验
			\lvlctl\getexp(calculate_attack_exp_gain($pa, $pd, $active), $pa);
	}
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		apply_weapon_imp($pa, $pd, $active);
		
		apply_weapon_skill_gain($pa, $pd, $active);
		
		$chprocess($pa, $pd, $active);
	}
	
	function attack_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa, $pd, $active);
		
		apply_attack_exp_gain($pa, $pd, $active);
	}
		
	function calculate_counter_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		return $counter_obbs[$pa['wep_kind']];
	}
	
	function calculate_counter_rate_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1.0;
	}
	
	function check_counter_dice(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$counter_rate = calculate_counter_rate ($pa, $pd, $active) * calculate_counter_rate_multiplier ($pa, $pd, $active);
		$counter_dice = rand ( 0, 99 );
		if ($counter_dice < $counter_rate) 
			return 1;
		else  return 0;
	}

	//再次注意，这里active代表的是是否是当前玩家
	function get_weapon_range(&$pa, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		if (isset($pa['wep_kind']))
			return $rangeinfo[$pa['wep_kind']];
		else  return $rangeinfo[$pa['wepk'][1]];
	}
	
	//No comment
	function get_weapon_range_counterer(&$pa, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		if (isset($pa['wep_kind']))
			return $rangeinfo[$pa['wep_kind']];
		else  return $rangeinfo[$pa['wepk'][1]];
	}
	
	function get_weapon_range_counteree(&$pa, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		if (isset($pa['wep_kind']))
			return $rangeinfo[$pa['wep_kind']];
		else  return $rangeinfo[$pa['wepk'][1]];
	}
	
	function check_can_counter(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r1 = get_weapon_range_counterer($pa, $active);
		$r2 = get_weapon_range_counteree($pd, 1-$active);
		if ($r1 >= $r2 && $r1 != 0 && $r2 != 0)
		{
			if (!$chprocess($pa,$pd,$active)) return 0;
			return check_counter_dice($pa, $pd, $active);
		}
		else  return 0;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'W' ) === 0)
		{
			$eqp = 'wep';
			$noeqp = 'WN';
			
			if (($noeqp && strpos ( ${$eqp.'k'}, $noeqp ) === 0) || ! ${$eqp.'s'}) {
				${$eqp} = $itm;
				${$eqp.'k'} = $itmk;
				${$eqp.'e'} = $itme;
				${$eqp.'s'} = $itms;
				${$eqp.'sk'} = $itmsk;
				$log .= "装备了<span class=\"yellow\">$itm</span>。<br>";
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
			} else {
				swap(${$eqp},$itm);
				swap(${$eqp.'k'},$itmk);
				swap(${$eqp.'e'},$itme);
				swap(${$eqp.'s'},$itms);
				swap(${$eqp.'sk'},$itmsk);
				$log .= "卸下了<span class=\"red\">$itm</span>，装备了<span class=\"yellow\">${$eqp}</span>。<br>";
			}
			return;
		}
		$chprocess($theitem);
	}
}

?>
