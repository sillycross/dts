<?php

namespace skill211
{
	//隐蔽和先制率提高
	$hidegain = Array(0,2,4,6,8,10);
	$actgain = Array(0,2,4,6,8,10);
	//升级所需技能点数值
	$upgradecost = Array(2,3,3,4,4,-1);
	
	function init() 
	{
		define('MOD_SKILL211_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[211] = '潜行';
	}
	
	function acquire211(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(211,'lvl','0',$pa);
	}
	
	function lost211(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked211(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade211()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill211','player','logger'));
		if (!\skillbase\skill_query(211))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(211,'lvl');
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
		$skillpoint-=$ucost; \skillbase\skill_setvalue(211,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function get_skill211_extra_hide_gain(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill211','player','logger'));
		if (!\skillbase\skill_query(211, $pa) || !check_unlocked211($pa)) return 0;
		//if ($pa['wep_kind']!='D') return 0;
		$hidegainrate = $hidegain[\skillbase\skill_getvalue(211,'lvl',$pa)];
		return $hidegainrate;
	}
	
	function get_skill211_extra_act_gain(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill211','player','logger'));
		if (!\skillbase\skill_query(211, $pa) || !check_unlocked211($pa)) return 1;
		//$pa['wep_kind'] = \weapon\get_attack_method($pa);
		//if ($pa['wep_kind']!='D') return 1;
		$actgainrate = $actgain[\skillbase\skill_getvalue(211,'lvl',$pa)];
		return 1+($actgainrate)/100;
	}
	
	function calculate_hide_obbs(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(211,$edata) || !check_unlocked211($edata)) return $chprocess($edata);
		return $chprocess($edata)+get_skill211_extra_hide_gain($edata);
	}
	
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	//只提高自己主动攻击时的先攻率
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$var = 1;
		if (\skillbase\skill_query(211,$ldata) && check_unlocked211($ldata)) {
			$var = get_skill211_extra_act_gain($ldata, $edata);
			if($var != 1) $ldata['active_words'] = \attack\multiply_format($var, $ldata['active_words'],0);
		}
		return $chprocess($ldata,$edata)*$var;
	}
}

?>
