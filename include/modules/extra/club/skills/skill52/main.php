<?php

namespace skill52
{
	global $upgradecost,$counterperc;
	$upgradecost=Array(2,2,2,2,3,3,-1);
	$counterperc=Array(0,20,40,60,80,100,125);
	
	function init() 
	{
		define('MOD_SKILL52_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[52] = '臂力';
	}
	
	function acquire52(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(52,'lvl','0',$pa);
	}
	
	function lost52(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(52,'lvl',$pa);
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
	
	function check_unlocked52(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade52()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill52','player','logger'));
		if (!\skillbase\skill_query(52) || !check_unlocked52($sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}

		$clv = \skillbase\skill_getvalue(52,'lvl');
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
		$clv++; \skillbase\skill_setvalue(52,'lvl',(string)$clv);
		$log.='升级成功。<br>';
	}
	
	function get_skill52_extra_counter_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill52','player','logger'));
		if (!\skillbase\skill_query(52, $pa) || !check_unlocked52($pa)) return 1;
		if ($pa['wepk'][1]!='C') return 1;
		$crate = $counterperc[\skillbase\skill_getvalue(52,'lvl',$pa)];
		return 1+($crate)/100;
	}
	
	function calculate_counter_rate_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(52,$pa) || !check_unlocked52($pa)) return $chprocess($pa, $pd, $active);
		if ($pa['wepk'][1]!='C') return $chprocess($pa, $pd, $active);
		$r=get_skill52_extra_counter_gain($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)*$r;
	}
}

?>
