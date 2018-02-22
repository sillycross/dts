<?php

namespace skill258
{
	//连击概率
	$procrate = Array(10,15,20,25,30);
	//升级所需技能点数值
	$upgradecost = Array(3,3,4,4,-1);
	
	function init() 
	{
		define('MOD_SKILL258_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[258] = '快拳';
	}
	
	function acquire258(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(258,'lvl','0',$pa);
	}
	
	function lost258(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(258,'lvl',$pa);
	}
	
	function check_unlocked258(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function check_wepk_debuff258($owep, $owepk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $owepk!='WN' && !($owepk=='WP' && strpos($owep,'拳')!==false);
	}
	
	function upgrade258()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill258','player','logger'));
		if (!\skillbase\skill_query(258))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(258,'lvl');
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
		$skillpoint-=$ucost; \skillbase\skill_setvalue(258,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function check_skill258_proc(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill258'));
		if (\skillbase\skill_query(258,$pa) && check_unlocked258($pa)){
			$l258=\skillbase\skill_getvalue(258,'lvl');
			if ($procrate[$l258]>=rand(0,99)) return 1;
		}
		return 0;
	}
	
	function check_rapid(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		eval(import_module('skill258'));
		if ($pa['wepk']=="WN" && \skillbase\skill_query(258,$pa) && check_unlocked258($pa) && check_skill258_proc($pa)) array_push($ret,'r');
		return $ret;
	}
}

?>