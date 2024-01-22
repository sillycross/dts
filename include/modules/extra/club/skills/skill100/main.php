<?php

namespace skill100
{
	function init()
	{
		define('MOD_SKILL100_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[100] = '沉心';
	}
	
	function acquire100(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(100, 'rageused', 0, $pa);
	}
	
	function lost100(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(100, 'rageused', $pa);
	}
	
	function check_unlocked100(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=6;
	}
	
	function upgrade100()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!(\skillbase\skill_query(100, $sdata) && check_unlocked100($sdata)))
		{
			$log .= '你没有这个技能！<br>';
			return;
		}
		$skill100_rageused = \skillbase\skill_getvalue(100,'rageused',$sdata);
		if ($skill100_rageused)
		{
			$log .= '你已经发动了此技能。<br>';
			return;
		}
		if ($rage <= 10)
		{
			$log .= '怒气不足。<br>';
			return;
		}
		\skillbase\skill_setvalue(100, 'rageused', $rage, $sdata);
		$rage = 0;
		$log .= '发动成功。<br>';
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		skill100_check();
		$chprocess();
	}
	
	function recipe_mix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		skill100_check();
		$chprocess();
	}
	
	function skill100_check()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(100, $sdata) && check_unlocked100($sdata))
		{
			$skill100_rageused = \skillbase\skill_getvalue(100,'rageused',$sdata);
			if ($skill100_rageused)
			{
				eval(import_module('sys','logger'));
				$log .= "<span class=\"yellow b\">你沉下心来，仔细雕琢着你的成果……</span><br>";
				\skillbase\skill_setvalue(100,'rageused',0,$sdata);
				if (in_array($itmk0[0], array('Y','Z','E'))) return;
				//合成产物的效果、耐久值强化
				$itme0 += round($skill100_rageused / 400 * $itme0);
				if ($itms0 != $nosta) $itms0 += round($skill100_rageused / 400 * $itms0);
			}
		}
	}
	
}

?>