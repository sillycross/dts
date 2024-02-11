<?php

namespace skill107
{
	$skill107_actrate = array(10,20,35,50);
	//升级所需技能点数值
	$upgradecost = array(2,3,3,-1);
	
	function init()
	{
		define('MOD_SKILL107_INFO','club;upgrade;feature;');
		eval(import_module('clubbase'));
		$clubskillname[107] = '窥秘';
		$clubdesc_h[27] = $clubdesc_a[25] = '获得7点初始理智值，损失理智值时可能获得一个随机技能';
	}
	
	function acquire107(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(107,'sanity','7',$pa);
	}
	
	function lost107(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(107,'sanity',$pa);
	}
	
	function check_unlocked107(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade107()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(107,$sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		eval(import_module('skill107'));
		$clv = (int)\skillbase\skill_getvalue(107,'lvl',$sdata);
		$ucost = $upgradecost[$clv];
		if ($ucost == -1)
		{
			$log .= '该技能已升级完成，不能继续升级！<br>';
			return;
		}
		if ($skillpoint < $ucost)
		{
			$log .= '技能点不足。<br>';
			return;
		}
		$skillpoint -= $ucost;
		\skillbase\skill_setvalue(107,'lvl',$clv+1,$sdata);
		$log .= '升级成功。<br>';
	}
	
	function skill107_lose_sanity($sandown, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(107,$pa)) return;
		$sanity = (int)\skillbase\skill_getvalue(107,'sanity',$pa);
		if ($sanity <= 0) return;
		\skillbase\skill_setvalue(107, 'sanity', max($sanity-$sandown, 0), $pa);
		eval(import_module('logger','skill107'));
		$log .= "<span class=\"red b\">陌生的知识冲击着你的头脑……</span><br>";
		$clv = (int)\skillbase\skill_getvalue(107,'lvl',$pa);
		if (rand(0,99) < $skill107_actrate[$clv])
		{
			eval(import_module('clubbase'));
			$newskillid = skill107_get_randskill($pa);
			if (!empty($newskillid)) $log .= "你习得了技能<span class=\"yellow b\">「{$clubskillname[$newskillid]}」</span>！<br>";
		}
	}
	
	function skill107_get_randskill(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('clubbase'));
		if (rand(0,4))
		{
			$rs_skills = \item_randskills\get_rand_clubskill($pa, 1, 'club27');
			$newskillid = $rs_skills[0];
		}
		else
		{
			eval(import_module('item_randskills'));
			$ls_skills = array_merge($rs_cardskills['S'], $rs_cardskills['A'], $rs_cardskills['B'], $rs_cardskills['C']);
			$newskillid = array_randompick($ls_skills, 1);
			\skillbase\skill_acquire($newskillid, $pa);
		}
		return $newskillid;
	}
	
}

?>