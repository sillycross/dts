<?php

namespace skill560
{
	$ragecost = 30;
	$skill560_gain = 200;
	
	function init() 
	{
		define('MOD_SKILL560_INFO','card;unique;battle;');
		eval(import_module('clubbase'));
		$clubskillname[560] = '碰瓷';
	}
	
	function acquire560(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost560(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked560(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_rage_cost560(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill560'));
		return $ragecost;
	}

	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill'] != 560) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(560,$pa) || !check_unlocked560($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost560($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,560) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「碰瓷」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「碰瓷」！</span><br>";
				$pa['rage'] -= $rcost;
				addnews ( 0, 'bskill560', $pa['name'], $pd['name'] );
				$temp_log = $log;
			}
			else
			{
				if ($active)
				{
					eval(import_module('logger'));
					$log.='怒气不足或其他原因不能发动。<br>';
				}
				$pa['bskill'] = 0;
			}
		}
		$chprocess($pa, $pd, $active);
		if (isset($temp_log)) $log = $temp_log;
	}

	function strike(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		if ($pa['bskill'] == 560) {
			eval(import_module('logger','player'));
			$log .= "<span class=\"yellow b\">你做好了顺势躺倒在地的准备！</span><br>";
			$pd['skill560_flag']=1;
		}
		else $chprocess($pa, $pd, $active);
	}

	function check_can_counter(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == $pa['skill560_flag']) return 1;
		return $chprocess($pa, $pd, $active);
	}

	function player_attack_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		if (!($pa['is_counter'] && \skillbase\skill_query(560,$pd) && 1 == $pa['skill560_flag'])) $chprocess($pa, $pd, $active);
	}
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		eval(import_module('player'));
		if (\skillbase\skill_query(560,$pd) && 1 == $pa['skill560_flag']) {
			if (isset($pd['inf'])) {
				eval(import_module('logger'));
				if (strlen($pd['inf']) > 0) {
					eval(import_module('skill560'));
					$gain = min(strlen($pd['inf']) * $skill560_gain, $pa['money']);
					if (1-$active) {
						if ($gain > 0) $log.="<span class=\"yellow b\">你靠着出色的演技，向对方索取了{$gain}元！</span><br>";
						else $log.="<span class=\"yellow b\">你的演技相当不错，但对方穷得连一个子儿也掏不出来了！</span><br>";
					}
					else {
						if ($gain > 0) $log.="<span class=\"yellow b\">{$pd['name']}靠着出色的演技，向你索取了{$gain}元！</span><br>";
						else $log.="<span class=\"yellow b\">对方的演技相当不错，但你穷得连一个子儿也掏不出来了！</span><br>";
					}
					$pd['money'] += $gain;
					$pa['money'] -= $gain;
				}
				else {
					if (1-$active) $log.="<span class=\"yellow b\">你没事人一样的演技没能得到对方的认可！</span><br>";
					else $log.="<span class=\"yellow b\">对方没事人一样的演技没能得到你的认可！</span><br>";
				}
			}
		}
		$chprocess($pa, $pd, $active);
	}

	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));		
		if (\skillbase\skill_query(560,$pd) && 1 == $pa['skill560_flag']) {
			if ($pd['hp'] <= 0) {
				eval(import_module('logger'));
				if (1-$active) $log .= "<span class=\"red b\">哎呀……看来你今天撞了大运！</span><br>";
				else $log .= "<span class=\"red b\">哎呀……看来对方今天撞了大运！</span><br>";
			}
		}
		$chprocess($pa, $pd, $active);
	}

}

?>