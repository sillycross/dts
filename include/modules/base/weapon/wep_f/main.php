<?php

namespace wep_f
{
	function init() 
	{
		eval(import_module('weapon','itemmain'));
		if (defined('MOD_NOISE')) eval(import_module('noise'));
		
		//武器类型依赖的熟练度名称
		$skillinfo['F'] = 'wf';
		//武器类型攻击动词
		$attinfo['F'] = '灵击';
		$attinfo2['F'] = '释放灵力攻击';
		//武器类型名
		$iteminfo['WF'] = '灵力兵器';
		
		//基础反击率
		$counter_obbs['F'] = 35;
		//射程
		$rangeinfo['F'] = 1;
		//基础命中率
		$hitrate_obbs['F'] = 85;
		//各种攻击方式的最高命中率
		$hitrate_max_obbs['F'] = 96;
		//每点熟练增加的命中
		$hitrate_r['F'] = 0.1;
		//各种攻击方式的伤害变动范围，越少越稳定。
		$dmg_fluc['F'] = 10;
		//每点熟练度增加的伤害
		$skill_dmg['F'] = 0.3;
		//各种攻击方式的武器损伤概率
		$wepimprate['F'] = 10000;
		//以该类武器击杀敌人后的死亡状态标号
		$wepdeathstate['F'] = 29;
		
		//声音信息
		if (defined('MOD_NOISE')) $noiseinfo['F'] = '灵气';
	}
	
	function get_WF_fixed_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['wepe'];
	}
	
	//灵系从固伤阶段改到主伤阶段
	function get_primary_fixed_dmg_base(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=0;
		if ($pa['wep_kind']=='F') $r=get_WF_fixed_dmg($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)+$r;
	}
	
//	function get_fixed_dmg(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$r=0;
//		if ($pa['wep_kind']=='F') $r=get_WF_fixed_dmg($pa, $pd, $active);
//		return $chprocess($pa, $pd, $active)+$r;
//	}
	
	function get_WF_sp_cost(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return max(round(0.2*$pa['wepe']),1);
	}
	
	function get_WF_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['wep_kind']!='F') return Array();
		eval(import_module('logger'));
		$spd_full=get_WF_sp_cost($pa, $pd, $active); 
		$spd_actual=min($pa['sp']-1,$spd_full);
		$dmg_mul=0.5+$spd_actual/$spd_full*0.5;
		$dmg_mul_word=round($dmg_mul*100);
		$pa['sp']-=$spd_actual;
		if ($active)
			$log.="你消耗{$spd_actual}点体力，发挥了灵力武器{$dmg_mul_word}％的威力！";
		else  $log.="{$pa['name']}消耗{$spd_actual}点体力，发挥了灵力武器{$dmg_mul_word}％的威力！";
		return Array(round($dmg_mul*100)/100);
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=get_WF_dmg_multiplier($pa, $pd, $active);
		if (isset($r[0]) && $r[0]==1) $r=Array();
		return array_merge($r,$chprocess($pa,$pd,$active));	
	}
	
	function check_WF_noise(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function weapon_strike(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (defined('MOD_NOISE'))
			if ($pa['wep_kind']=='F' && check_WF_noise($pa, $pd, $active)) 
				\noise\addnoise($pa['pls'],'F',$pa['pid'],$pd['pid']);
		$chprocess($pa, $pd, $active);
	}
}

?>
