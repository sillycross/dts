<?php

namespace skill74
{

	function init() 
	{
		define('MOD_SKILL74_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[74] = '心火';
	}
	
	function acquire74(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost74(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked74(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=19;
	}
	
	function get_rage_cost74(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill74'));
		if ($pa===NULL)
		{
			eval(import_module('player'));
			$clb=$club;
		}
		else  $club=$pa['club'];
		return ($club==9?60:85);
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=74) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(74,$pa) || !check_unlocked74($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost74($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,74) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「心火」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「心火」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill74', $pa['name'], $pd['name'] );
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
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ($pa['bskill']==74) 
		{
			eval(import_module('logger'));
			if ($active)
				$log.='<span class="lime b">你对敌人释放出必杀技！</span><br>';
			else  $log.='<span class="lime b">敌人对你释放出必杀技！</span><br>';
			$x=($pa['club']==9?2:1.7);
			$r=Array($x);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill74') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「心火」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
