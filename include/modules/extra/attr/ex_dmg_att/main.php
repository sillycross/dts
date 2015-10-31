<?php

namespace ex_dmg_att
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['p'] = '带毒';
		$itemspkinfo['u'] = '火焰';
		$itemspkinfo['i'] = '冻气';
		$itemspkinfo['e'] = '电击';
		$itemspkinfo['w'] = '音波';
		$itemspkinfo['d'] = '爆炸';
		$itemspkinfo['f'] = '灼焰';
		$itemspkinfo['k'] = '冰华';
		//声音信息
		if (defined('MOD_NOISE')) 
		{
			eval(import_module('noise'));
			$noiseinfo['d'] = '爆炸声';
		}
	}
	
	//计算单个属性伤害
	function get_basic_ex_dmg(&$pa,&$pd,$active,$key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att'));
		$damage = $ex_base_dmg[$key]+$pa['wepe']/$ex_wep_dmg[$key]+$pa['fin_skill']/$ex_skill_dmg[$key];
		return $damage;
	}
	
	function calculate_ex_single_original_dmg(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att'));
		//基础伤害+武器效/属性的武器效果成长+熟练/属性的熟练成长
		$damage=get_basic_ex_dmg($pa,$pd,$active,$key);
		//最大伤害限制公式
		if ($ex_max_dmg[$key]>0) $damage = $ex_max_dmg[$key] * $damage / ( $damage + $ex_max_dmg[$key] / 2 );
		//得意武器类型翻倍
		if ($pa['wep_kind']==$ex_good_wep[$key]) $damage *= 2;
		//浮动
		$damage = $damage * rand(100 - $ex_dmg_fluc[$key], 100 + $ex_dmg_fluc[$key]) / 100;
		return $damage;
	}
	
	function get_ex_inf_dmg_punish(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att'));
		return $ex_inf_punish[$key];
	}
	
	function calculate_ex_inf_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//计算异常状态影响
		eval(import_module('ex_dmg_att','wound','logger'));
		$r = 1;
		if (isset($ex_inf[$key]))
		{
			if (\skillbase\skill_query($ex_inf[$key], $pd))
			{
				$infkey = array_search($ex_inf[$key], $infskillinfo);
				$punish = get_ex_inf_dmg_punish($pa, $pd, $active, $key);
				if ($punish != 1)
				{
					if ($punish > 1) $punish_word = "倍增"; else $punish_word = "减少";
					if ($active)
						$log .= "由于{$pd['name']}已经{$infname[$infkey]}，{$exdmgname[$key]}伤害{$punish_word}！";
					else  $log .= "由于你已经{$infname[$infkey]}，{$exdmgname[$key]}伤害{$punish_word}！";
				}
				$r *= $punish;
			}
		}
		return $r;
	}
	
	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return calculate_ex_inf_multiple($pa, $pd, $active, $key);
	}
	
	//计算属性伤害造成异常状态的概率
	function calculate_ex_inf_rate(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','ex_dmg_att','wound','logger'));
		if (!isset($ex_inf[$key])) return 0; 	//本属性无可以造成的异常状态
		if (\skillbase\skill_query($ex_inf[$key], $pd)) return 0;	//敌方已经有此异常状态了
		$rate = $ex_inf_r[$key]+$pa['fin_skill']*$ex_skill_inf_r[$key];
		return min($rate,$ex_max_inf_r[$key]);
	}
	
	function get_ex_inf_rate_modifier(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 0;
	}
	
	function check_ex_inf_infliction(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','ex_dmg_att','wound','logger'));
		//判定造成异常状态
		$inf_rate = calculate_ex_inf_rate($pa, $pd, $active, $key)+get_ex_inf_rate_modifier($pa, $pd, $active, $key);
		$inf_dice = rand(0,99);
		if ($inf_dice < $inf_rate)
		{
			$infkey = array_search($ex_inf[$key], $infskillinfo);
			if ($active)
				$log .= "并造成{$pd['name']}{$infname[$infkey]}了！";
			else  $log .= "并造成你{$infname[$infkey]}了！";
			\wound\get_inf($infkey,$pd);
			addnews($now,'inf',$pa['name'],$pd['name'],$infkey);
		}
	}
	
	//执行单个属性攻击
	function calculate_ex_single_dmg(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','ex_dmg_att','wound','logger'));
		//计算加减成
		$damage_multiple = calculate_ex_single_dmg_multiple($pa, $pd, $active, $key);
		//计算基础伤害
		$damage = calculate_ex_single_original_dmg($pa, $pd, $active, $key);
		//最终伤害
		$damage *= $damage_multiple;
		$damage = round($damage); if ($damage < 1) $damage = 1;
		$pa['ex_dmg_'.$key.'_dealt'] = $damage;
		if ($pd['ex_dmg_'.$key.'_defend_success'] == 1)	//恶心一下吧…… 奇怪的log美观修正……
			$log .= "造成了<span class=\"red\">{$damage}</span>点额外伤害！";
		else  $log .= "{$exdmgname[$key]}造成了<span class=\"red\">{$damage}</span>点额外伤害！";
		//判断造成异常状态
		check_ex_inf_infliction($pa, $pd, $active, $key);
		$log.='<br>';
		return $damage;
	}	
	
	//计算属性伤害
	function calculate_ex_attack_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att'));
		$tot = 0;
		$ex_attack_array = \attrbase\get_ex_attack_array($pa, $pd, $active);
		foreach ( $ex_attack_list as $key )
			if (in_array($key, $ex_attack_array))
			{
				$damage = calculate_ex_single_dmg($pa, $pd, $active, $key);
				$pa['ex_dmg_dealt'] +=$damage;
				$tot += $damage;
			}
		return $tot;
	}
			
	function strike(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa, $pd, $active);
		
		if ($pa['is_hit'] && strpos($pa['wepk'],$pa['wep_kind'])!==false)	//命中才开始判定属性伤害，枪械作为钝器使用无属性伤害
		{
			$dmg = calculate_ex_attack_dmg($pa, $pd, $active);
			$pa['dmg_dealt'] += $dmg;
		}
	}
	
	//战斗前清空各类计数器
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att'));
		$pa['ex_dmg_dealt'] = 0;
		foreach ( $ex_attack_list as $key ) $pa['ex_dmg_'.$key.'_dealt'] = 0;
		$chprocess($pa, $pd, $active);
	}
	
	function add_ex_att_noise(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (defined('MOD_NOISE') && in_array('d',\attrbase\get_ex_attack_array($pa, $pd, $active)))
		{
			\noise\addnoise($pa['pls'],'d',$pa['pid'],$pd['pid']);
		}
	}
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		add_ex_att_noise($pa, $pd, $active);
		$chprocess($pa, $pd, $active);
	}
}

?>
