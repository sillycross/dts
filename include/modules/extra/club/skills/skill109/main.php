<?php

namespace skill109
{
	$skill109_tempskill_time = 300;
	
	function init()
	{
		define('MOD_SKILL109_INFO','club;upgrade;locked;');
		eval(import_module('clubbase'));
		$clubskillname[109] = '博闻';
	}
	
	function acquire109(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(109,'unused','1',$pa);
	}
	
	function lost109(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(109,'unused',$pa);
	}
	
	function check_unlocked109(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=3;
	}
	
	function upgrade109()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!(\skillbase\skill_query(109, $sdata) && check_unlocked109($sdata)))
		{
			$log .= '你没有这个技能！<br>';
			return;
		}
		if (\skillbase\skill_getvalue(109,'unused',$sdata)) \skillbase\skill_delvalue(109,'unused',$sdata);
		else
		{
			if ($skillpoint < 2)
			{
				$log .= '技能点不足。<br>';
				return;
			}
			$skillpoint -= 2;
		}
		if (\skillbase\skill_query(107, $sdata) && (rand(0,99) < 35)) \skill107\skill107_lose_sanity(1, $sdata);
		$rs_skills = \item_randskills\get_rand_clubskill($sdata, 1, 'club27');
		if (!empty($rs_skills))
		{
			eval(import_module('sys','clubbase','skill109'));
			$log .= "你习得了技能<span class=\"yellow b\">「{$clubskillname[$rs_skills[0]]}」</span>，持续时间<span class=\"cyan b\">$skill109_tempskill_time</span>秒！<br>";
			\skillbase\skill_setvalue($rs_skills[0], 'tsk_expire', $now + $skill109_tempskill_time);
		}
		else $log .= "好像什么也没有发生。<br>";
	}
	
}

?>