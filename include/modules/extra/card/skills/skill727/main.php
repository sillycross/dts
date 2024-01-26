<?php

namespace skill727
{
	$ragecost = 50;
	
	function init()
	{
		define('MOD_SKILL727_INFO','card;battle;');
		eval(import_module('clubbase'));
		$clubskillname[727] = '整理';
	}
	
	function acquire727(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(727,'acount','0',$pa);
	}
	
	function lost727(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(727,'acount',$pa);
	}
	
	function check_unlocked727(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_rage_cost727(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill727'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=727) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(727,$pa) || !check_unlocked727($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost727($pa);
			if (!\clubbase\check_battle_skill_unactivatable($pa,$pd,727) )
			{
				eval(import_module('logger'));
				if ($active)
					$log .= "<span class=\"lime b\">你对{$pd['name']}发动了技能「整理」！</span><br>";
				else $log .= "<span class=\"lime b\">{$pa['name']}对你发动了技能「整理」！</span><br>";
				if (\skillbase\skill_query(104,$pa) && \skill104\check_unlocked104($pa))
				{
					$acount = (int)\skillbase\skill_getvalue(727,'acount',$pa);
					\skillbase\skill_setvalue(727,'acount',$acount+1,$pa);
					if ($active) $log .= "<span class=\"lime b\">碎片整理使你的状态更加集中了！</span><br>";
					else $log .= "<span class=\"lime b\">碎片整理使{$pa['name']}的状态更加集中了！</span><br>";
				}
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill727', $pa['name'], $pd['name'] );
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
	
	function get_skill104_actrate(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = $chprocess($pa);
		if (\skillbase\skill_query(727,$pa) && check_unlocked727($pa))
		{
			$acount = (int)\skillbase\skill_getvalue(727,'acount',$pa);
			$r += 5 * $acount;
			$r = min($r, 100);
		}
		return $r;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if ($pa['bskill']==727) 
		{
			eval(import_module('logger'));
			if ($active) $log .= "<span class=\"lime b\">碎片整理使你造成的伤害增加了10%！</span><br>";
			else $log .= "<span class=\"lime b\">碎片整理使{$pa['name']}造成的伤害增加了10%！</span><br>";
			$r = array(1.1);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill727')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「整理」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>