<?php

namespace skill82
{

	$ragecost=5;
	
	$wep_skillkind_req = 'wk';
	
	function init() 
	{
		define('MOD_SKILL82_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[82] = '解牛';
	}
	
	function acquire82(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost82(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked82(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_rage_cost82(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill82'));
		return $ragecost;
	}
	
	function get_dmg82(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']+30;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=82) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(82,$pa) || !check_unlocked82($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost82($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,82) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「解牛」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「解牛」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill82', $pa['name'], $pd['name'] );
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
	
	function get_final_dmg_base(&$pa, &$pd, &$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if ($pa['bskill']==82 && $pa['is_hit']) 
		{
			eval(import_module('logger'));
			$d=get_dmg82($pa);
			$log.='<span class="yellow b">「解牛」附加了'.$d.'点伤害！</span><br>';
			$ret += $d;
			$pa['mult_words_fdmgbs'] = \attack\add_format($d, $pa['mult_words_fdmgbs']);
		}
		return $ret;
	}
	
	//武器损耗率减半
	function calculate_wepimp_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if($pa['bskill']==82 && \weapon\get_skillkind($pa,$pd,$active) == 'wk') $ret/=2;
		return $ret;
	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill82') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「解牛」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>