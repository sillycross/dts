<?php

namespace skill101
{
	function init()
	{
		define('MOD_SKILL101_INFO','club;upgrade;locked;');
		eval(import_module('clubbase'));
		$clubskillname[101] = '铸血';
	}
	
	function acquire101(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(101, 'activated', 0, $pa);
	}
	
	function lost101(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(101, 'activated', $pa);
	}
	
	function check_unlocked101(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=10;
	}
	
	function upgrade101()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!(\skillbase\skill_query(101, $sdata) && check_unlocked101($sdata)))
		{
			$log .= '你没有这个技能！<br>';
			return;
		}
		$skill101_act = \skillbase\skill_getvalue(101,'activated',$sdata);
		if ($skill101_act)
		{
			$log .= '你已经发动了此技能。<br>';
			return;
		}
		if ($mhp <= 50)
		{
			$log .= '生命上限不足。<br>';
			return;
		}
		\skillbase\skill_setvalue(101, 'activated', 1, $sdata);
		$mhp -= 50;
		if ($hp > $mhp + 50) $hp -= 50;
		else $hp = min($hp, $mhp);
		$log .= '发动成功。<br>';
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		skill101_check();
		$chprocess();
	}
	
	function recipe_mix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		skill101_check();
		$chprocess();
	}
	
	function skill101_check()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(101, $sdata) && check_unlocked101($sdata))
		{
			$skill101_act = \skillbase\skill_getvalue(101,'activated',$sdata);
			if ($skill101_act)
			{
				eval(import_module('sys','logger'));
				$log .= "<span class=\"yellow b\">你的鲜血化为了神奇的力量！</span><br>";
				\skillbase\skill_setvalue(101,'activated',0,$sdata);
				skill101_proc();
			}
		}
	}
	
	function skill101_proc()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		//获得额外属性
		$tmpsk = array();
		if (in_array(substr($itmk0, 0, 2), array('HH','HS','HB')))
		{
			$tmpsk[] = 'Z';
		}
		elseif ($itmk0[0] == 'W')
		{
			$tmpsk = array_randompick(array('f','k','t','d','r','N','L','n','y','^ac1','u','e','i','w','p','N','H','z'), 3);
		}
		elseif ($itmk0[0] == 'D')
		{
			$tmpsk = array_randompick(array('B','b','Z','h','A','a','^wc1','P','K','G','C','D','F','R','q','U','I','E','W','H','M','m','z'), 3);
		}
		foreach($tmpsk as $sk)
		{
			if (!\itemmain\check_in_itmsk($sk, $itmsk0)) $itmsk0 .= $sk;
		}
		//类别强化
		$dice = rand(0,99);
		if ($itmk0[0] == 'W')
		{
			if ($dice < 40)
			{
				//复合武器不会改系
				if (in_array(substr($itmk0, 2, 1), array('P','K','G','C','D','F','J','B'))) return;
				eval(import_module('weapon'));
				$skill = array();
				foreach($skillinfo as $skiv)
				{
					$skill[$skiv] = ${$skiv};
				}
				arsort($skill);
				$skill_keys = array_keys($skill);
				$nowsk = $skillinfo[substr($itmk0, 1, 1)];
				$maxsk = $skill_keys[0];
				if ($skill[$nowsk] != $skill[$maxsk])
				{
					$changek = array('wp' => 'WP', 'wk' => 'WK', 'wg' => 'WG', 'wc' => 'WC', 'wd' => 'WD', 'wf' => 'WF');
					$itmk0 = $changek[$maxsk].substr($itmk0,2);
					if (strpos($itm0, '-改') === false) $itm0 = $itm0.'-改';
				}
			}
			if ($dice < 1)
			{
				if (substr($itmk0, 0, 2) == 'WG') $itmk0 = 'WJ'.substr($itmk0,2);
				elseif (substr($itmk0, 0, 2) == 'WC') $itmk0 = 'WB'.substr($itmk0,2);
				if (strpos($itm0, '-改') === false) $itm0 = $itm0.'-改';
			}
		}
		elseif ($itmk0[0] == 'D')
		{
			if (($dice < 40) && (strpos(substr($itmk0,2),'S') === false))
			{
				$itmk0 = substr($itmk0, 0, 2).'S'.substr($itmk0, 2);
				if (strpos($itm0, '-改') === false) $itm0 = $itm0.'-改';
			}
		}
		elseif ($itmk0[0] == 'H')
		{
			$itmk0_type = substr($itmk0, 0, 2);
			if (($itmk0_type == 'HH') || ($itmk0_type == 'HS')) $itmk0 = 'HB'.substr($itmk0,2);
			elseif ($itmk0_type == 'HB')
			{
				if ($dice < 8)
				{
					if ($dice < 1)
					{
						$itmk0 = 'HM'.substr($itmk0,2);
						$itms0 = rand(2,4);
					}
					elseif ($dice < 3)
					{
						$itmk0 = 'HT'.substr($itmk0,2);
						$itms0 = rand(2,4);
					}
					else $itmk0 = 'HR'.substr($itmk0,2);
					if (strpos($itm0, '-改') === false) $itm0 = $itm0.'-改';
				}
			}
		}
	}
	
}

?>