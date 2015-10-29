<?php

namespace skill217
{
	$dmggain = Array(10,20,30,40,50);
	$upgradecost = Array(6,6,7,7,-1);
	
	function init() 
	{
		define('MOD_SKILL217_INFO','club;upgrade;locked;');
		eval(import_module('clubbase'));
		$clubskillname[217] = '特攻';
	}
	
	function acquire217(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(217,'lvl','0',$pa);
	}
	
	function lost217(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
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
	
	function check_unlocked217(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade217()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill217','player','logger'));
		if (!\skillbase\skill_query(217))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(217,'lvl');
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
		$skillpoint-=$ucost; \skillbase\skill_setvalue(217,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function get_skill217_extra_acc_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill217','player','logger'));
		if (!\skillbase\skill_query(217, $pa) || !check_unlocked217($pa)) return 1;
		if ($pa['wep_kind']!='G') return 1;
		$accgainrate = $accgain[\skillbase\skill_getvalue(217,'lvl',$pa)];
		return 1+($accgainrate)/100;
	}
	
	function get_skill217_extra_dmg_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill217','player','logger'));
		if (!\skillbase\skill_query(217, $pa) || !check_unlocked217($pa)) return 1;
		$d = $dmggain[\skillbase\skill_getvalue(217,'lvl',$pa)];
		return 1+($d/100);
	}
	
	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(217,$pa) && check_unlocked217($pa))
			return $chprocess($pa, $pd, $active, $key)*get_skill217_extra_dmg_gain($pa, $pd, $active);
		else	return $chprocess($pa, $pd, $active, $key);
	}
}

?>
