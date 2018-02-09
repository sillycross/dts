<?php

namespace skill35
{
	//物理伤害增加
	$attgain = Array(20,50,80);
	//晕眩时间
	$sk35_stuntime = Array(1,1,2);
	//升级所需技能点数值
	$upgradecost = Array(10,11,-1);
	//触发率
	$proc_rate = 25;
	
	function init() 
	{
		define('MOD_SKILL35_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[35] = '猛击';
	}
	
	function acquire35(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(35,'lvl','0',$pa);
	}
	
	function lost35(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(35,'lvl',$pa);
	}
	
	function check_unlocked35(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade35()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill35','player','logger'));
		if (!\skillbase\skill_query(35))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(35,'lvl');
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
		$skillpoint-=$ucost; \skillbase\skill_setvalue(35,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function calculate_skill35_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill35'));
		return $proc_rate;
	}
	
	function check_skill35_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill35','player','logger'));
		if (!\skillbase\skill_query(35, $pa) || !check_unlocked35($pa)) return Array();
		if (\weapon\get_skillkind($pa,$pd,$active) != 'wp') return Array();
		if (rand(0,99)<calculate_skill35_proc_rate($pa,$pd,$active))
		{
			if ($active)
				$log.="<span class=\"yellow\">你朝着{$pd['name']}打出了凶猛的一击！<span class=\"clan\">敌人被打晕了过去！</span></span><br>";
			else  $log.="<span class=\"yellow\">{$pa['name']}朝你打出了凶猛的一击！<span class=\"clan\">你被打晕了过去！</span></span><br>";
			$clv = (int)\skillbase\skill_getvalue(35,'lvl',$pa);
			$dmggain = (100+$attgain[$clv])/100;
			\skill602\set_stun_period($sk35_stuntime[$clv]*1000,$pd);
			\skill602\send_stun_battle_news($pa['name'],$pd['name']);
			return Array($dmggain);
		}
		return Array();
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = check_skill35_proc($pa,$pd,$active);
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
}

?>