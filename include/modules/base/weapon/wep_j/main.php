<?php

namespace wep_j
{
	//重枪默认无法发动任何战斗技能，但以下技能可以发动。在具体技能模块里设定
	$wj_allowed_bskill = array();
	
	function init() 
	{
		eval(import_module('weapon','itemmain'));
		if (defined('MOD_NOISE')) eval(import_module('noise'));
		
		//武器类型依赖的熟练度名称
		$skillinfo['J'] = 'wg';
		//武器类型攻击动词
		$attinfo['J'] = '狙击';
		//武器类型名
		$iteminfo['WJ'] = '重型枪械';
		
		//基础反击率
		$counter_obbs['J'] = 20;
		//射程
		$rangeinfo['J'] = 8;
		//基础命中率
		$hitrate_obbs['J'] = 10;
		//各种攻击方式的最高命中率
		$hitrate_max_obbs['J'] = 98;
		//每点熟练增加的命中
		$hitrate_r['J'] = 0.2;
		//各种攻击方式的伤害变动范围，越少越稳定。
		$dmg_fluc['J'] = 10;
		//每点熟练度增加的伤害
		$skill_dmg['J'] = 0.7;
		//各种攻击方式的武器损伤概率
		$wepimprate['J'] = 10000;
		//以该类武器击杀敌人后的死亡状态标号
		$wepdeathstate['J'] = 23;

		//声音信息
		if (defined('MOD_NOISE')) $noiseinfo['J'] = '枪声';
	}
	
	function get_WJ_fixed_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$damage = min(round($pd['mhp']/3),20000) + round($pa['wepe']*2/3);
		return $damage;
	}
	
	//重枪从固伤阶段改到主伤阶段
	function get_primary_fixed_dmg_base(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=0;
		if ($pa['wep_kind']=='J') $r=get_WJ_fixed_dmg($pa, $pd, $active);
		return $r+$chprocess($pa, $pd, $active);
	}
	
//	function get_fixed_dmg(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$r=0;
//		if ($pa['wep_kind']=='J') $r=get_WJ_fixed_dmg($pa, $pd, $active);
//		return $r+$chprocess($pa, $pd, $active);
//	}
	
	function get_attack_method(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		if (substr($pdata['wepk'],1,1) == 'J' && $pdata['weps']==$nosta) {
			return 'P';
		}
		else  return $chprocess($pdata);
	}
	
	function get_WJ_att_as_WP_modifier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 0.1;
	}
	
	function get_external_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		if ($pa['wep_kind']=='P' && substr($pa['wepk'],1,1) == 'J')
			return get_WJ_att_as_WP_modifier($pa,$pd,$active)*$chprocess($pa, $pd, $active);
		else  return $chprocess($pa, $pd, $active);
	}
	
	function calculate_wepimp_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		$r=1; 
		if ($pa['wep_kind']=='P' && substr($pa['wepk'],1,1) == 'J') $r=6;	//射系作钝器使用时损坏特判
		return $chprocess($pa, $pd, $active)*$r;
	}
	
	function apply_weapon_imp(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('weapon','logger'));
		if ($pa['wep_kind']=='J')	//射系武器log提示改变
		{
			if (isset($pa['wepimp']) && $pa['wepimp'])
			{
				$pa['weps']-=$pa['wepimp'];
				if ($active)
					$log .= "你的{$pa['wep']}的弹药数减少了{$pa['wepimp']}。<br>";
				else  $log .= "{$pa['name']}的{$pa['wep']}的弹药数减少了{$pa['wepimp']}。<br>";
				
				if ($pa['weps']<=0) \weapon\weapon_break($pa, $pd, $active);
			}
		}
		else  $chprocess($pa,$pd,$active);
	}
	
	function weapon_break(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('weapon','logger'));
		if ($pa['wep_kind']=='J')	//射系武器损坏特判（子弹用光）
		{
			if ($active)
				$log .= "你的<span class=\"red b\">{$pa['wep']}</span>的弹药用光了！<br>";
			else  $log .= "{$pa['name']}的<span class=\"red b\">{$pa['wep']}</span>的弹药用光了！<br>";
			$pa['weps']=$nosta;
		}
		else  $chprocess($pa,$pd,$active);
	}
	
	function weapon_WJ_addnoise(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (defined('MOD_NOISE'))
			\noise\addnoise($pa['pls'],'J',$pa['pid'],$pd['pid']);
	}
	
	function weapon_strike(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (defined('MOD_NOISE'))
			if ($pa['wep_kind']=='J') 
				weapon_WJ_addnoise($pa, $pd, $active);
		$chprocess($pa, $pd, $active);
	}
	
	function get_internal_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['wep_kind']=='J')
		{
			$val = $chprocess($pa,$pd,$active);
			if ($val > 340) $val = 340 + ($val - 340) / 3;	//射系武器基础攻击折损
			return $val;
		}
		else  return $chprocess($pa,$pd,$active);
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (strpos($pd['wepk'],'J')!==false)	//防守方持重型枪械受到额外伤害
		{
			eval(import_module('logger'));
			if ($active)
				$log.="<span class=\"red b\">由于{$pd['name']}手中的武器过于笨重，受到的伤害大增！</span><br>";
			else  $log.="<span class=\"red b\">由于你手中的武器过于笨重，受到的伤害大增！真是大快人心啊！</span><br>";
			$r=Array(1.5);
		}
		return array_merge($r,$chprocess($pa, $pd, $active));
	}
	
	//重枪忽略战斗技能
	//这个函数应该是“与”的关系
	function check_battle_skill_available(&$edata,$skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','wep_j'));
		//var_dump(in_array($skillno, $wj_allowed_bskill));
		if (strpos($wepk, 'J')!==false && !in_array($skillno, $wj_allowed_bskill)) return false;
		else return $chprocess($edata,$skillno);
	}

	//重枪忽略战斗技能
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('wep_j'));
		if (strpos($pa['wepk'], 'J')!==false && !in_array($pa['bskill'], $wj_allowed_bskill)) $pa['bskill']=0;
		
		$chprocess($pa, $pd, $active);
	}
	
}

?>