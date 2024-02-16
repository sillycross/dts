<?php

namespace skill203
{

	$ragecost=20;
	
	$wep_skillkind_req = 'wg';
	
	function init() 
	{
		define('MOD_SKILL203_INFO','club;battle;');
		eval(import_module('clubbase','wep_j'));
		$clubskillname[203] = '瞄准';
		$wj_allowed_bskill[] = 203;
	}
	
	function acquire203(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost203(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked203(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=3;
	}
	
	function get_rage_cost203(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill203'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=203) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(203,$pa) || !check_unlocked203($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost203($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,203))
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「瞄准」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「瞄准」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill203', $pa['name'], $pd['name'] );
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
	
	function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($pa, $pd, $active);
		if ($pa['bskill']!=203) return $ret;
		return $ret*1.15;
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ($pa['bskill']==203) 
		{
			eval(import_module('logger'));
			if ($active)
				$log.='<span class="yellow b">「瞄准」使你造成的物理伤害提高了20%！</span><br>';
			else  $log.='<span class="yellow b">「瞄准」使敌人造成的物理伤害提高了20%！</span><br>';
			$r=Array(1.2);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill203') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「瞄准」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
