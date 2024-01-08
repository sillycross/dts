<?php

namespace skill86
{
	$ragecost = 50;
	$moneycost = 500;
	
	function init()
	{
		define('MOD_SKILL86_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[86] = '喝令';
	}
	
	function acquire86(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost86(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked86(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl'] >= 15;
	}

	function get_rage_cost86(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill86'));
		return $ragecost;
	}
	
	function check_battle_skill_unactivatable(&$ldata,&$edata,$skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($ldata,$edata,$skillno);
		if ((86 == $skillno) && (0 == $ret))
		{
			eval(import_module('skill86'));
			if ($ldata['money'] < $moneycost) $ret = 6;
			elseif (!\skillbase\skill_query(56,$ldata) || !\skill56\check_unlocked56($ldata)) $ret = 6;
			elseif (empty(\skillbase\skill_getvalue(56,'t',$ldata))) $ret = 6;
		}
		return $ret;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill'] != 86) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(86,$pa) || !check_unlocked86($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill'] = 0;
		}
		else
		{
			$rcost = get_rage_cost86($pa);
			if (!\clubbase\check_battle_skill_unactivatable($pa,$pd,86))
			{
				eval(import_module('logger'));
				if ($active)
					$log .= "<span class=\"lime b\">你对{$pd['name']}发动了技能「喝令」！</span><br>";
				else  $log .= "<span class=\"lime b\">{$pa['name']}对你发动了技能「喝令」！</span><br>";
				$pa['rage'] -= $rcost;
				addnews(0, 'bskill86', $pa['name'], $pd['name']);
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
	
	function attack(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(86,$pa) && check_unlocked86($pa) && \skillbase\skill_query(56,$pa) && \skill56\check_unlocked56($pa))
		{
			eval(import_module('logger','skill86'));
			if ($pa['bskill'] == 86) $skill86_procrate = 50;
			else $skill86_procrate = 10;
			$skill86_flag = 0;
			$x=(int)\skillbase\skill_getvalue(56,'t',$pa);
			for ($i=1; $i<=$x; $i++)
			{
				if (($pa['bskill'] == 86) && ($pa['money'] < $moneycost)) break;
				if (rand(0,99) < $skill86_procrate)
				{
					$is_hired=(int)\skillbase\skill_getvalue(56,'h'.$i,$pa);
					if ($is_hired==1)
					{
						$spid = (int)\skillbase\skill_getvalue(56,'p'.$i,$pa);
						if ($spid>0)
						{
							$employee=\player\fetch_playerdata_by_pid($spid);
							if (($employee['hp']>0) && ($employee['pls']==$pa['pls']))
							{
								$log .= "<span class=\"cyan b\">{$employee['name']}的追加攻击！</span><br>";
								//补充攻击方式和必要参数
								$employee['skill86_flag'] = 1;
								$employee['wep_kind'] = $employee['wepk'][1];
								$employee['bskill'] = 0;
								$employee['fin_skill'] = \weapon\get_skill($employee,$pd,$active);
								$employee['fin_hitrate'] = \weapon\get_hitrate($employee,$pd,$active);
								$employee['mult_words_fdmgbs'] = '';
								$employee['physical_dmg_dealt'] = 0;
								$employee['dmg_dealt'] = 0;
								$employee['actual_rapid_time'] = 0;
								$employee['wepimp'] = 0;
								
								$chprocess($employee,$pd,$active);
								//付钱
								if ($pa['bskill'] == 86)
								{
									$pa['money'] -= $moneycost;
									$employee['money'] += $moneycost;
									$log .= "<span class=\"yellow b\">你向{$employee['name']}支付了{$moneycost}元！</span><br>";
								}
								$skill86_flag = 1;
								\player\player_save($employee);
								//伤害追加到主玩家伤害
								if ($employee['dmg_dealt'])
								{
									$pa['dmg_dealt'] += $employee['dmg_dealt'];
									$pa['mult_words_fdmgbs'] = \attack\add_format($employee['dmg_dealt'], $pa['mult_words_fdmgbs']);
								}
							}
						}
					}
				}
			}
			if ((!$skill86_flag) && ($pa['bskill'] == 86)) $log .= "<span class=\"yellow b\">你一个人也没能叫来！</span><br>";
		}	
	}
	
	//助攻不显示耐久扣减log
	function apply_weapon_imp(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$temp_log = $log;
        $chprocess($pa, $pd, $active);
		if ($employee['skill86_flag'] == 1) $log = $temp_log;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill86') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「喝令」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}

}

?>