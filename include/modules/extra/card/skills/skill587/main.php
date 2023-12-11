<?php

namespace skill587
{
	$ragecost = 30;
	
	function init() 
	{
		define('MOD_SKILL587_INFO','card;battle;');
		eval(import_module('clubbase'));
		$clubskillname[587] = '废线';
	}
	
	function acquire587(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost587(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked587(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_rage_cost587(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill587'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill'] != 587) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(587,$pa) || !check_unlocked587($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill'] = 0;
		}
		else
		{
			$rcost = get_rage_cost587($pa);
			if ($pa['rage'] >= $rcost)
			{
				eval(import_module('skill587','logger'));
				if ($active) $log .= "<span class=\"lime b\">你对{$pd['name']}发动了技能「废线」！</span><br>";
				else $log .= "<span class=\"lime b\">{$pa['name']}对你发动了技能「废线」！</span><br>";
				$pa['rage'] -= $rcost;
				addnews ( 0, 'bskill587', $pa['name'], $pd['name'] );
			}
			else
			{
				if ($active)
				{
					eval(import_module('logger'));
					$log .= '怒气不足或其他原因不能发动。<br>';
				}
				$pa['bskill'] = 0;
			}
		}
		$chprocess($pa, $pd, $active);
	}
	
	function get_fixed_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 0;
		if (($pa['bskill'] == 587) && \skillbase\skill_query(586, $pa))
		{
			$odds = 100;
			$count = 0;
			while ($odds > 0)
			{
				if (rand(0,99) < $odds)
				{
					$count += 1;
					$odds -= 12;
				}
				else break;
			}
			$skill586_itmarr = \skill586\skill586_prepare_itmarr($pa);
			$skill586_npcarr = \skill586\skill586_get_npcarr($pa);
			$skill586_nowcount_item = sizeof($skill586_itmarr);
			$skill586_nowcount_npc = sizeof($skill586_npcarr);
			$skill586_totalcount = $skill586_nowcount_item + $skill586_nowcount_npc;
			$count = min($skill586_totalcount, $count);
			if ($count > 0)
			{
				eval(import_module('logger'));
				$dice = rand(0,3);
				$spell = (array('「来自异次元的归还」','「异次元苏生」','「轨道大甩卖」','境界「溢出的干垃圾」'))[$dice];
				$log .= "<span class=\"yellow b\">你打开隙间，发动了{$spell}！</span><br>";
				if ($dice == 3) $log .= "<img src=\"img/garbage.gif\"><br>";
				$ls = range(1, $skill586_totalcount);
				shuffle($ls);
				for($i=0;$i<$count;$i++)
				{
					if ($ls[$i] <= $skill586_nowcount_item)
					{
						$ret = \skill586\skill586_fetchout_core($ls[$i]-1, $pa);
						if (!empty($ret))
						{
							if (\searchmemory\searchmemory_available())
							{
								$dropid = \itemmain\itemdrop_query($ret['itm'], $ret['itmk'], $ret['itme'], $ret['itms'], $ret['itmsk'], $pa['pls']);
								$amarr = array('iid' => $dropid, 'itm' => $ret['itm'], 'pls' => $pa['pls'], 'unseen' => 0);
								\skill1006\add_beacon($amarr, $pa);
							}
							$fdmg = skill587_dmg_item($ret);
							$log .= "<span class=\"yellow b\">{$ret['itm']}造成了{$fdmg}点额外物理伤害！</span><br>";
						}
					}
					else
					{
						$tpid = $skill586_npcarr[$ls[$i]-$skill586_nowcount_item-1];
						$tnpc = \player\fetch_playerdata_by_pid($tpid);
						if (!empty($tnpc))
						{
							\skill586\skill586_release($tpid, $pa);
							$fdmg = skill587_dmg_npc($tnpc);
							$log .= "<span class=\"yellow b\">{$tnpc['name']}造成了{$fdmg}点额外物理伤害！</span><br>";
						}
					}
				}
			}
		}
		return $chprocess($pa, $pd, $active)+$r;
	}
	
	function skill587_dmg_item($theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = sqrt($theitem['itme']);
		if ($theitem['itms'] == '∞') $r *= 1.5;
		else $r *= max(1, log10($theitem['itms']));
		if ($theitem['itmk'][0] == 'W') $r *= 1.3;
		$r = max(round(1.5*$r), 1);
		return $r;
	}
	
	function skill587_dmg_npc($pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = sqrt($pa['hp']) + sqrt($pa['att']);
		$r = max(round(1.2*$r), 1);
		return $r;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill587') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「废线」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>