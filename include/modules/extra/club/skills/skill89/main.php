<?php

namespace skill89
{
	//升级所需技能点数值
	$upgradecost = array(4,4,-1);
	
	function init()
	{
		define('MOD_SKILL89_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[89] = '强音';
	}
	
	function acquire89(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(89,'lvl','0',$pa);
	}
	
	function lost89(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked89(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=3;
	}
	
	function upgrade89()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill89','player','logger'));
		if (!(\skillbase\skill_query(89, $sdata) && check_unlocked89($sdata)))
		{
			$log .= '你没有这个技能！<br>';
			return;
		}
		$clv = (int)\skillbase\skill_getvalue(89, 'lvl', $sdata);
		$ucost = $upgradecost[$clv];
		if ($ucost == -1)
		{
			$log .= '你已经升级完成了，不能继续升级！<br>';
			return;
		}
		if ($skillpoint < $ucost)
		{
			$log .= '技能点不足。<br>';
			return;
		}
		$skillpoint -= $ucost;
		\skillbase\skill_setvalue(89, 'lvl', $clv+1);
		$log .= '升级成功。<br>';
	}
	
	//添加变奏属性
	function check_itmsk($nm, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!$pa)
		{
			eval(import_module('player'));
			$pa = $sdata;
		}
		if (($nm == '^sv') && \skillbase\skill_query(89, $pa) && check_unlocked89($pa)) return true;
		return $chprocess($nm, $pa);
	}
	
	//添加音波属性
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(89, $pa) && check_unlocked89($pa) && ((int)\skillbase\skill_getvalue(89, 'lvl', $pa) >= 1)) array_push($ret,'w');
		return $ret;
	}
	
	//添加激奏1属性
	function get_ex_def_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(89, $pd) && check_unlocked89($pd) && ((int)\skillbase\skill_getvalue(89, 'lvl', $pd) >= 2)) array_push($ret,'^sa1');
		return $ret;
	}
	
}

?>