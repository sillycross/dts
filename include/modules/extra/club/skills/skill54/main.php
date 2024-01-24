<?php

namespace skill54
{
	global $upgradecost,$dmgreduction;
	$upgradecost=Array(8,-1);
	$dmgreduction=Array(15,30);
	
	function init() 
	{
		define('MOD_SKILL54_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[54] = '圣盾';
	}
	
	function acquire54(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(54,'lvl','0',$pa);
	}
	
	function lost54(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(54,'lvl',$pa);
	}
	
	function check_unlocked54(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=8;
	}
	
	function upgrade54()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill54','player','logger'));
		if (!\skillbase\skill_query(54) || !check_unlocked54($sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}

		$clv = \skillbase\skill_getvalue(54,'lvl');
		$clv = (int)$clv;
		if ($upgradecost[$clv]==-1)
		{
			$log.='你已经升到满级了。<br>';
			return;
		}
		if ($skillpoint<$upgradecost[$clv])
		{
			$log.='技能点不足。<br>';
			return;
		}
		$skillpoint -= $upgradecost[$clv];
		$clv++; \skillbase\skill_setvalue(54,'lvl',(string)$clv);
		$log.='升级成功。<br>';
	}
	
	function ex_attack_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(54,$pd) || !check_unlocked54($pd)) return $chprocess($pa, $pd, $active);
		if (count(\attrbase\get_ex_attack_array($pa,$pd,$active))>0)
		{
			eval(import_module('logger'));
			if ($active)
				$log.='<span class="yellow b">技能「圣盾」降低了敌人受到的属性伤害！</span><br>';
			else  $log.='<span class="yellow b">技能「圣盾」降低了你受到的属性伤害！</span><br>';
		}
		return $chprocess($pa, $pd, $active);
	}
	
	function calculate_ex_attack_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(54,$pd) && check_unlocked54($pd)) 
		{
			eval(import_module('skill54'));
			$clv = \skillbase\skill_getvalue(54,'lvl',$pd);
			$clv = (int)$clv;
			$r=Array(1-$dmgreduction[$clv]/100);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
//	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if (!\skillbase\skill_query(54,$pd) || !check_unlocked54($pd)) return $chprocess($pa, $pd, $active, $key);
//		eval(import_module('skill54'));
//		$clv = \skillbase\skill_getvalue(54,'lvl',$pd);
//		$clv = (int)$clv;
//		return $chprocess($pa, $pd, $active, $key)*(1-$dmgreduction[$clv]/100);
//	}	
}

?>