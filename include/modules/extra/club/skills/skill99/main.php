<?php

namespace skill99
{
	$skill99_actrate = array(20,30,40,55);
	//升级所需技能点数值
	$upgradecost = array(2,2,3,-1);
	
	function init()
	{
		define('MOD_SKILL99_INFO','club;upgrade;locked;');
		eval(import_module('clubbase'));
		$clubskillname[99] = '精工';
	}
	
	function acquire99(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(99,'lvl','0',$pa);
	}
	
	function lost99(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked99(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=3;
	}
	
	function upgrade99()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill99','player','logger'));
		if (!\skillbase\skill_query(99, $sdata) || !check_unlocked99($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = (int)\skillbase\skill_getvalue(99,'lvl', $sdata);
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
		\skillbase\skill_setvalue(99, 'lvl', $clv+1);
		$log .= '升级成功。<br>';
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		skill99_check();
		$chprocess();
	}
	
	function recipe_mix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		skill99_check();
		$chprocess();
	}
	
	function skill99_check()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(99, $sdata) && check_unlocked99($sdata))
		{
			if (in_array($itmk0[0], array('W','D')))
			{
				eval(import_module('skill99'));
				$clv = (int)\skillbase\skill_getvalue(99, 'lvl', $sdata);
				if (rand(0,99) < $skill99_actrate[$clv])
				{
					eval(import_module('logger'));
					$log .= "<span class=\"yellow b\">在你神乎其神的技巧下，合成产物发出了光芒！</span><br>";
					skill99_proc();
				}
			}
		}
	}
	
	//合成产物的效果、耐久、属性强化
	function skill99_proc()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));		
		$itme0 = round((1 + rand(0,40)/100) * $itme0);
		if ($itms0 != $nosta) $itms0 = round((1 + rand(0,40)/100) * $itms0);
		if (rand(0,99) < 5)
		{
			if ($itmk0[0] == 'W') $tmpsk = array_randompick(array('f','k','t','d','r','n','y'));
			if ($itmk0[0] == 'D') $tmpsk = array_randompick(array('B','b','Z','h','A','A','a','a'));
			if (!\itemmain\check_in_itmsk($tmpsk, $itmsk0)) $itmsk0 .= $tmpsk;
		}
		if ($itmk0[0] == 'W') $tmpsk = array_randompick(array('u','e','i','w','p','N','H','z'));
		if ($itmk0[0] == 'D') $tmpsk = array_randompick(array('A','a','P','K','G','C','D','F','R','q','U','I','E','W','H','M','m','z'));
		if (!\itemmain\check_in_itmsk($tmpsk, $itmsk0)) $itmsk0 .= $tmpsk;
	}
	
}

?>