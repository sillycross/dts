<?php

namespace skill212
{
	//提高回避陷阱和重复利用的概率
	$evgain = Array(0,2,4,6,8,10);
	$reugain = Array(0,4,8,12,16,20);
	//升级所需技能点数值
	$upgradecost = Array(2,2,2,3,3,-1);
	
	function init() 
	{
		define('MOD_SKILL212_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[212] = '谨慎';
	}
	
	function acquire212(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(212,'lvl','0',$pa);
	}
	
	function lost212(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked212(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade212()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill212','player','logger'));
		if (!\skillbase\skill_query(212))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(212,'lvl');
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
		$skillpoint-=$ucost; \skillbase\skill_setvalue(212,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function get_skill212_extra_ev_gain()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill212','player','logger'));
		if (!\skillbase\skill_query(212) || !check_unlocked212()) return 0;
		$evgainrate = $evgain[\skillbase\skill_getvalue(212,'lvl')];
		return $evgainrate;
	}
	
	function get_skill212_extra_reuse_gain()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill212','player','logger'));
		if (!\skillbase\skill_query(212) || !check_unlocked212()) return 1;
		$reuserate = $reugain[\skillbase\skill_getvalue(212,'lvl')];
		return $reuserate;
	}
	
	function get_trap_escape_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(212)) return $chprocess()+get_skill212_extra_ev_gain(); else return $chprocess();
	}
	
	function calculate_trap_reuse_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(212)) return $chprocess()+get_skill212_extra_reuse_gain(); else return $chprocess();
	}
}

?>
