<?php

namespace skill239
{
	//命中率获得比例
	$sk239_ratio = Array(10,16,24,32,40);
	//升级所需技能点数值
	$upgradecost = Array(5,5,6,6,-1);
	
	function init() 
	{
		define('MOD_SKILL239_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[239] = '对偶';
	}
	
	function acquire239(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(239,'lvl','0',$pa);
		\skillbase\skill_setvalue(239,'spent','0',$pa);
	}
	
	function lost239(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked239(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade239()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill239','player','logger'));
		if (!\skillbase\skill_query(239))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(239,'lvl');
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
		$skillpoint-=$ucost; \skillbase\skill_setvalue(239,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function get_skill239_percentage(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill239','player','logger'));
		$clv = (int)\skillbase\skill_getvalue(239,'lvl',$pa);
		return $sk239_ratio[$clv];
	}
	
	function get_skill(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		if (!\skillbase\skill_query(239, $pa) || !check_unlocked239($pa)) return $chprocess($pa,$pd,$active);
		return $chprocess($pa,$pd,$active)+round($pa['att']*get_skill239_percentage($pa, $pd, $active)/100.0);
	}
}

?>
