<?php

namespace skill202
{
	//致伤率提高
	$infrgain = Array(0,50,100,150);//没有防具受损率这个说法
	//额外耐久削减
	$exthit2 = Array(0,1,2,4);
	//每处致伤提高最终伤害
	$extrdmg = Array(0,10,20,30);
	//升级所需技能点数值
	$upgradecost = Array(6,6,7,-1);
	
	$upgradecount = Array(0,6,12,19);
	
	function init() 
	{
		define('MOD_SKILL202_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[202] = '破甲';
	}
	
	function acquire202(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(202,'lvl','0',$pa);
		\skillbase\skill_setvalue(202,'spent','0',$pa);
	}
	
	function lost202(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked202(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade202()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill202','player','logger'));
		if (!\skillbase\skill_query(202))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(202,'lvl');
		$ucost = $upgradecost[$clv];
		if ($clv == -1)
		{
			$log.='你已经升级完成了，不能继续升级！<br>';
			return;
		}
		if ($skillpoint<$ucost) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		$skillpoint-=$ucost; \skillbase\skill_setvalue(202,'lvl',$clv+1);
		\skillbase\skill_setvalue(202,'spent',$upgradecount[$clv+1],$pa);
		$log.='升级成功。<br>';
	}
	
	function get_skill202_extra_inf_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill202','player','logger'));
		if (!\skillbase\skill_query(202, $pa) || !check_unlocked202($pa)) return 1;
		if (\weapon\get_skillkind($pa,$pd,$active) != 'wg') return 1;
		$infrgainrate = $infrgain[\skillbase\skill_getvalue(202,'lvl',$pa)];
		return 1+($infrgainrate)/100;
	}
	
	function calculate_inf_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(202,$pa) || !check_unlocked202($pa)) return $chprocess($pa, $pd, $active);
		$t=get_skill202_extra_inf_rate($pa, $pd, $active);
		return $t*$chprocess($pa, $pd, $active);
	}
	
	function get_skill202_extra_wounds(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill202','player','logger'));
		if (!\skillbase\skill_query(202, $pa) || !check_unlocked202($pa)) return 0;
		if (\weapon\get_skillkind($pa,$pd,$active) != 'wg') return 0;
		$extrahit2 = $exthit2[\skillbase\skill_getvalue(202,'lvl',$pa)];
		return $extrahit2; 
	}
	
	function calculate_weapon_wound_multiplier(&$pa, &$pd, $active, $hurtposition) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(202,$pa) || !check_unlocked202($pa)) return $chprocess($pa, $pd, $active, $hurtposition);
		return $chprocess($pa, $pd, $active, $hurtposition)*(1+get_skill202_extra_wounds($pa, $pd, $active));
	}
	
	function get_skill202_extra_dmgrate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill202','player','logger'));
		if (!\skillbase\skill_query(202, $pa) || !check_unlocked202($pa)) return 0;
		if (\weapon\get_skillkind($pa,$pd,$active) != 'wg') return 0;
		$extd = $extrdmg[\skillbase\skill_getvalue(202,'lvl',$pa)];
		return $extd; 
	}
	
	function apply_weapon_wound_real(&$pa, &$pd, $active, $hurtposition)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active, $hurtposition);
		
		if ((\skillbase\skill_query(202,$pa))&&(check_unlocked202($pa))&&(\weapon\get_skillkind($pa,$pd,$active) == 'wg'))
		{
			$pa['skill202_count']++;
		}
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa, $pd, $active);

		if ((\skillbase\skill_query(202,$pa))&&(check_unlocked202($pa))&&(\weapon\get_skillkind($pa,$pd,$active) == 'wg'))
		{
			$pa['skill202_count']=0;
		}
	}
		
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ((\skillbase\skill_query(202,$pa))&&(check_unlocked202($pa))&&(\weapon\get_skillkind($pa,$pd,$active) == 'wg'))
		{
			$var_202=$pa['skill202_count']*get_skill202_extra_dmgrate($pa,$pd,$active);
			eval(import_module('logger'));
			if ($var_202>0)
			{
				if ($active)
					$log.="<span class=\"yellow\">「破甲」使你造成的最终伤害提高了{$var_202}%！</span><br>";
				else  $log.="<span class=\"yellow\">「破甲」使敌人造成的最终伤害提高了{$var_202}%！</span><br>";
				$r=Array(1+$var_202/100);
			}	
			unset($pa['skill202_count']);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
}

?>
