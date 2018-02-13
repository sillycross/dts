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
	
	function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(65,$pd) || !check_unlocked65($pd)) return $ret;
		eval(import_module('skill65'));
		$clv = (int)\skillbase\skill_getvalue(65,'lvl',$pd);
		return $ret*(1-$dodgerate[$clv]/100);
	}
	
	function get_rapid_accuracy_loss(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(65,$pd) || !check_unlocked65($pd)) return $chprocess($pa, $pd, $active);
		eval(import_module('skill65'));
		$clv = (int)\skillbase\skill_getvalue(65,'lvl',$pd);
		return $chprocess($pa, $pd, $active)*(1-$rhitpunish[$clv]/100);
	}
	
	function get_WF_sp_cost(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(65,$pa) || !check_unlocked65($pa)) return $chprocess($pa, $pd, $active);
		eval(import_module('skill65'));
		$clv = (int)\skillbase\skill_getvalue(65,'lvl',$pa);
		return round($chprocess($pa, $pd, $active)*(1-$spredrate[$clv]/100));
	}
}

?>
