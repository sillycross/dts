<?php

namespace skill60
{
	function init() 
	{
		define('MOD_SKILL60_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[60] = '恐惧';
	}
	
	function acquire60(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost60(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked60(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=12;
	}
	
	function get_rage_cost60(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill60','player'));
		if ($pa===NULL) 
		{
			$clv = $lvl; $clb = $club;
		}
		else 
		{
			$clv = $pa['lvl']; $clb = $pa['club'];
		}
		if ($clb==24)
			return max(20,50-$clv);
		else  return max(25,50-round($clv*0.7));
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=60) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(60,$pa) || !check_unlocked60($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost60($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,60) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「恐惧」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「恐惧」！</span><br>";
				$pa['rage']-=$rcost;
				$pa['skill60_flag']=floor((1-min(1,$pd['hp']/$pd['mhp']))*100);
				addnews ( 0, 'bskill60', $pa['name'], $pd['name'] );
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
		if ($pa['bskill']==60) 
		{
			eval(import_module('logger'));
			$r=Array(1+$pa['skill60_flag']/100);
			if ($active)
				$log.='<span class="yellow b">敌人因惊吓过度，受到的物理伤害增加了'.$pa['skill60_flag'].'%！</span><br>';
			else  $log.='<span class="yellow b">你因惊吓过度，受到的物理伤害增加了'.$pa['skill60_flag'].'%！</span><br>';
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']==60) unset($pa['skill60_flag']);
		$chprocess($pa,$pd,$active);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill60') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「恐惧」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
