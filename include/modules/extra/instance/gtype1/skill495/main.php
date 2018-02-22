<?php

namespace skill495
{
	$moneylimit = Array(10000,15000,25000,36000);
	$upgradecost = Array(5,12,15,-1);
	
	function init() 
	{
		define('MOD_SKILL495_INFO','card;unique;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[495] = '额度';
	}
	
	function acquire495(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(495,'lvl','0',$pa);
	}
	
	function lost495(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked495(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade495()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill495','player','logger'));
		if (!\skillbase\skill_query(495))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(495,'lvl');
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
		$skillpoint-=$ucost; \skillbase\skill_setvalue(495,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function get_money_limit495($pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill495'));
		if(!\skillbase\skill_query(495,$pa)) return 9999999999;
		$lvl495 = (int)\skillbase\skill_getvalue(495,'lvl', $pa);
		return $moneylimit[$lvl495];
	}
	
	function check_can_pick_money($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill495','sys','player'));
		if(\skillbase\skill_query(495) && $money + $edata['money'] > get_money_limit495($sdata)) {
			eval(import_module('corpse'));
			$cannot_pick_notice = '超过你金钱容量，无法拾取';
			return false;
		}
		return $chprocess($edata);
	}
}

?>