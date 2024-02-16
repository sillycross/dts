<?php

namespace skill236
{

	$ragecost=15;
	
	$stuntime236 = 1000;
	
	function init() 
	{
		define('MOD_SKILL236_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[236] = '科学';
	}
	
	function acquire236(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost236(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked236(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=3;
	}
	
	function get_rage_cost236(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill236'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=236) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(236,$pa) || !check_unlocked236($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost236($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,236) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「科学」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「科学」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill236', $pa['name'], $pd['name'] );
			}
			else
			{
				if ($active)
				{
					eval(import_module('logger'));
					$log.='怒气不足或其他原因不能发动。<br>';
				}
				$pa['bskill']=0;
			}
		}
		$chprocess($pa, $pd, $active);
	}	
	
	function get_fixed_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		if ($pa['bskill']!=236) return $chprocess($pa,$pd,$active);
		if ($active) $log .= "<span class=\"red b\">你掏出撬棍猛击敌人！</span><span class=\"cyan b\">敌人被你打晕了过去！</span><br>";
			else $log .= "<span class=\"red b\">敌人掏出撬棍猛击你！</span><span class=\"cyan b\">你被打晕了过去！</span><br>";
		eval(import_module('skill236'));
		\skill602\set_stun_period($stuntime236,$pd);
		\skill602\send_stun_battle_news($pa['name'],$pd['name']);
		return $chprocess($pa, $pd, $active)+60;
	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill236') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「科学」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
