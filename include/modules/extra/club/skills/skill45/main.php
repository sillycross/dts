<?php

namespace skill45
{
	//怒气消耗
	$ragecost = 20; 
	
	function init() 
	{
		define('MOD_SKILL45_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[45] = '重拳';
	}
	
	function acquire45(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost45(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked45(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=3;
	}
	
	function get_rage_cost45(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill45'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=45) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(45,$pa) || !check_unlocked45($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost45($pa);
			if (!\clubbase\check_battle_skill_unactivatable($pa,$pd,45))
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「重拳」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「重拳」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill45', $pa['name'], $pd['name'] );
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
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ($pa['bskill']==45) 
		{
			eval(import_module('logger'));
			$r=Array(1.3);
			if ($active)
				$log.='<span class="yellow b">你向敌人打出了一记重拳！</span><br>';
			else  $log.='<span class="yellow b">敌人向你打出了一记重拳！</span><br>';
		}
		return array_merge($r,$chprocess($pa, $pd, $active));
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill45') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「重拳」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
