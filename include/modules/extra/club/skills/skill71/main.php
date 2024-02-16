<?php

namespace skill71
{

	function init() 
	{
		define('MOD_SKILL71_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[71] = '解构';
	}
	
	function acquire71(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost71(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked71(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=3;
	}
	
	function get_rage_cost71(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill71'));
		if ($pa===NULL)
		{
			eval(import_module('player'));
			$clb=$club;
		}
		else  $club=$pa['club'];
		return ($club==18?18:25);
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=71) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(71,$pa) || !check_unlocked71($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost71($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,71) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「解构」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「解构」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill71', $pa['name'], $pd['name'] );
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
		if ($pa['bskill']==71) 
		{
			eval(import_module('logger'));
			if ($active)
				$log.='<span class="yellow b">「解构」使你造成的物理伤害提高了20%！</span><br>';
			else  $log.='<span class="yellow b">「解构」使敌人造成的物理伤害提高了20%！</span><br>';
			$r=Array(1.2);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}

	function calculate_skill71_expgain(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$coef=($pa['club']==18?0.15:0.3);
		return max(0,round($pd['lvl']-$pa['lvl']*$coef));
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']==71)
		{
			$expgain=calculate_skill71_expgain($pa,$pd,$active);
		}
		$chprocess($pa,$pd,$active);
		if ($pa['bskill']==71)
		{
			eval(import_module('logger'));			
			if ($active)
				$log.='<span class="yellow b">「解构」使你获得了额外'.$expgain.'点经验！</span><br>';
			else  $log.='<span class="yellow b">「解构」使敌人获得了额外'.$expgain.'点经验！</span><br>';
			\lvlctl\getexp($expgain,$pa);
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill71') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「解构」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
