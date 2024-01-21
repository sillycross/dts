<?php

namespace skill97
{
	$skill97_getexp = array(0,1,1,2,3);
	$skill97_getsk = array(0,2,4,6,8);
	//升级所需技能点数值
	$upgradecost = array(2,2,2,2,-1);
	
	function init()
	{
		define('MOD_SKILL97_INFO','club;upgrade;feature;');
		eval(import_module('clubbase'));
		$clubskillname[97] = '妙手';
		$clubdesc_a[26] = '每次合成时获得2-4点经验值<br>称号特性升级后每次合成能获得额外的经验值和熟练度';
		$clubdesc_h[26] .= '每次合成时获得2-4点经验值<br>称号特性升级后每次合成能获得额外的经验值和熟练度';
	}
	
	function acquire97(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(97,'lvl','0',$pa);
	}
	
	function lost97(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(97,'lvl',$pa);
	}
	
	function check_unlocked97(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade97()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill97','player','logger'));
		if (!\skillbase\skill_query(97, $sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = (int)\skillbase\skill_getvalue(97,'lvl', $sdata);
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
		\skillbase\skill_setvalue(97, 'lvl', $clv+1, $sdata);
		$log .= '升级成功。<br>';
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		skill97_check();
		$chprocess();
	}
	
	function recipe_mix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		skill97_check();
		$chprocess();
	}
	
	function skill97_check()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(97,$sdata) && check_unlocked97($sdata)) 
		{
			eval(import_module('skill97'));
			$clv = (int)\skillbase\skill_getvalue(97, 'lvl', $sdata);
			$expup = rand(2,4) + $skill97_getexp[$clv];
			\lvlctl\getexp($expup,$sdata);
			if (($itmk0[0] == 'W') && $clv)
			{
				eval(import_module('weapon'));
				$resultsk = $skillinfo[substr($itmk0, 1, 1)];
				${$resultsk} += $skill97_getsk[$clv];
			}
		}
	}
	
}

?>