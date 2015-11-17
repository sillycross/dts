<?php

namespace skill65
{
	$upgradecost=Array(3,3,4,-1);
	//闪避率
	$dodgerate=Array(0,4,8,12);
	//连击命中衰减
	$rhitpunish=Array(0,2,3,4);
	//灵系体力消耗降低
	$spredrate=Array(40,50,60,70);
	
	function init() 
	{
		define('MOD_SKILL65_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[65] = '灵力';
	}
	
	function acquire65(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(65,'lvl','0',$pa);
	}
	
	function lost65(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(65,'lvl',$pa);
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function check_unlocked65(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade65()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill65','player','logger'));
		if (!\skillbase\skill_query(65) || !check_unlocked65($sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}

		$clv = \skillbase\skill_getvalue(65,'lvl');
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
		$clv++; \skillbase\skill_setvalue(65,'lvl',(string)$clv);
		$log.='升级成功。<br>';
	}
	
	function get_skill65_extra_dog_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill65','player','logger'));
		if (!\skillbase\skill_query(65, $pd) || !check_unlocked65($pd)) return 1;
		if ($pd['wepk'][1]!='F') return 1;
		$drate = $dodgerate[\skillbase\skill_getvalue(65,'lvl',$pd)];
		return 1-($drate)/100;
	}
	
	function get_hitrate(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(65,$pd) || !check_unlocked65($pd)) return $chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)*get_skill65_extra_dog_gain($pa, $pd, $active);
	}
	
	function get_skill65_extra_rdog_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill65','player','logger'));
		if (!\skillbase\skill_query(65, $pd) || !check_unlocked65($pd)) return 1;
		if ($pd['wepk'][1]!='F') return 1;
		$drate = $rhitpunish[\skillbase\skill_getvalue(65,'lvl',$pd)];
		return 1-($drate)/100;
	}
	
	function get_rapid_accuracy_loss(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(65,$pd) || !check_unlocked65($pd)) return $chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)*get_skill65_extra_rdog_gain($pa, $pd, $active);
	}
	
	function get_skill65_WF_cost_reduce(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill65','player','logger'));
		if (!\skillbase\skill_query(65, $pa) || !check_unlocked65($pa)) return 1;
		if ($pa['wep_kind']!='F') return 1;
		$drate = $spredrate[\skillbase\skill_getvalue(65,'lvl',$pa)];
		return 1-($drate)/100;
	}
	
	function get_WF_sp_cost(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(65,$pa) || !check_unlocked65($pa)) return $chprocess($pa, $pd, $active);
		return round($chprocess($pa, $pd, $active)*get_skill65_WF_cost_reduce($pa, $pd, $active));
	}
}

?>
