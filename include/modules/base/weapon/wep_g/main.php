<?php

namespace wep_g
{
	function init() 
	{
		eval(import_module('weapon','itemmain'));
		if (defined('MOD_NOISE')) eval(import_module('noise'));
		
		//武器类型依赖的熟练度名称
		$skillinfo['G'] = 'wg';
		//武器类型攻击动词
		$attinfo['G'] = '射击';
		//武器类型名
		$iteminfo['WG'] = '远程兵器';
		
		//基础反击率
		$counter_obbs['G'] = 50;
		//射程
		$rangeinfo['G'] = 7;
		//基础命中率
		$hitrate_obbs['G'] = 70;
		//各种攻击方式的最高命中率
		$hitrate_max_obbs['G'] = 95;
		//每点熟练增加的命中
		$hitrate_r['G'] = 0.05;
		//各种攻击方式的伤害变动范围，越少越稳定。
		$dmg_fluc['G'] = 20;
		//每点熟练度增加的伤害
		$skill_dmg['G'] = 0.6;
		//各种攻击方式的武器损伤概率
		$wepimprate['G'] = 10000;
		//以该类武器击杀敌人后的死亡状态标号
		$wepdeathstate['G'] = 23;

		//声音信息
		if (defined('MOD_NOISE')) $noiseinfo['G'] = '枪声';
	}
	
	//判定枪托攻击，更改wep_kind类型
	function get_attack_method(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		if (check_WG_att_as_WP($pdata)) {
			return 'P';
		}
		else  return $chprocess($pdata);
	}
	
	//判定枪托攻击的核心函数（例如追击等判定的时候需要调用）
	//如果是枪托攻击，返回true
	function check_WG_att_as_WP(&$pdata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		return (substr($pdata['wepk'],1,1) == 'G' && $pdata['weps']==$nosta);
	}
	
	function get_WG_att_as_WP_modifier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 0.1;
	}
	
	function get_external_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		if ($pa['wep_kind']=='P' && substr($pa['wepk'],1,1) == 'G')
			return get_WG_att_as_WP_modifier($pa,$pd,$active)*$chprocess($pa, $pd, $active);
		else  return $chprocess($pa, $pd, $active);
	}
	
	function calculate_wepimp_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		$r=1; 
		if ($pa['wep_kind']=='P' && substr($pa['wepk'],1,1) == 'G') $r=2.5;	//射系作钝器使用时损坏特判，结合无限耐久钝器损坏特判，总共6.25倍
		return $chprocess($pa, $pd, $active)*$r;
	}
	
	function apply_weapon_imp(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','weapon','logger'));
		if ($pa['wep_kind']=='G')	//射系武器log提示改变
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
		if ($pa['wep_kind']=='G')	//射系武器损坏特判（子弹用光）
		{
			if ($active)
				$log .= "你的<span class=\"red b\">{$pa['wep']}</span>的弹药用光了！<br>";
			else  $log .= "{$pa['name']}的<span class=\"red b\">{$pa['wep']}</span>的弹药用光了！<br>";
			$pa['weps']=$nosta;
		}
		else  $chprocess($pa,$pd,$active);
	}
	
	function weapon_WG_addnoise(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (defined('MOD_NOISE'))
			\noise\addnoise($pa['pls'],'G',$pa['pid'],$pd['pid']);
	}
	
	function weapon_strike(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (defined('MOD_NOISE'))
			if ($pa['wep_kind']=='G') 
				weapon_WG_addnoise($pa, $pd, $active);
		$chprocess($pa, $pd, $active);
	}
	
	function get_internal_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['wep_kind']=='G')
		{
			$val = $chprocess($pa,$pd,$active);
			if ($val > 270) $val = 270 + round(($val - 270) / 3 * 10 ) /10;	//射系武器基础攻击折损
			return $val;
		}
		else  return $chprocess($pa,$pd,$active);
	}
}

?>