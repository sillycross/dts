<?php

namespace skill105
{
	$ragecost = 35;
	
	function init()
	{
		define('MOD_SKILL105_INFO','club;battle;locked;');
		eval(import_module('clubbase'));
		$clubskillname[105] = '辉光';
	}
	
	function acquire105(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost105(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked105(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=5;
	}
	
	function get_rage_cost105(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill105'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=105) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(105,$pa) || !check_unlocked105($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost105($pa);
			if (!\clubbase\check_battle_skill_unactivatable($pa,$pd,105) )
			{
				eval(import_module('logger'));
				if ($active)
					$log .= "<span class=\"lime b\">你对{$pd['name']}发动了技能「辉光」！</span><br>";
				else $log .= "<span class=\"lime b\">{$pa['name']}对你发动了技能「辉光」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill105', $pa['name'], $pd['name'] );
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
		if (isset($pa['bskill']) && ($pa['bskill']==105)) return 70;
		return $chprocess($pa);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill105')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「辉光」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>