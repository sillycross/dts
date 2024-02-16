<?php

namespace skill85
{
	$upgradecost = 9;
	$ragecost = 25;
	
	function init() 
	{
		define('MOD_SKILL85_INFO','club;battle;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[85] = '神启';
	}
	
	function acquire85(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(85,'lvl','0',$pa);
	}
	
	function lost85(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(85,'lvl',$pa);
	}
	
	function check_unlocked85(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl'] >= 3;
	}

	function get_rage_cost85(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill85'));
		return $ragecost;
	}
	
	function upgrade85()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill85','player','logger'));
		if (!\skillbase\skill_query(85,$sdata))
		{
			$log .= '你没有这个技能！<br>';
			return;
		}
		$clv = (int)\skillbase\skill_getvalue(85,'lvl',$sdata);
		if ($clv >= 1)
		{
			$log .= '你已经升级完成了，不能继续升级！<br>';
			return;
		}
		if ($skillpoint < $upgradecost)
		{
			$log .= '技能点不足。<br>';
			return;
		}
		$skillpoint -= $upgradecost;
		\skillbase\skill_setvalue(85,'lvl','1',$sdata);
		$log .= '升级成功。<br>';
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill'] != 85) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(85,$pa) || !check_unlocked85($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill'] = 0;
		}
		else
		{
			$rcost = get_rage_cost85($pa);
			if (!\clubbase\check_battle_skill_unactivatable($pa,$pd,85))
			{
				eval(import_module('logger'));
				if ($active)
					$log .= "<span class=\"lime b\">你对{$pd['name']}发动了技能「神启」！</span><br>";
				else  $log .= "<span class=\"lime b\">{$pa['name']}对你发动了技能「神启」！</span><br>";
				$pa['rage'] -= $rcost;
				addnews(0, 'bskill85', $pa['name'], $pd['name']);
			}
			else
			{
				if ($active)
				{
					eval(import_module('logger'));
					$log .= '怒气不足或其他原因不能发动。<br>';
				}
				$pa['bskill'] = 0;
			}
		}
		$chprocess($pa, $pd, $active);
	}
	
	function get_final_dmg_base(&$pa, &$pd, &$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (($pa['bskill']==85) && $pa['is_hit']) 
		{
			eval(import_module('logger'));
			$log .= '<span class="yellow b">「神启」附加了40点伤害！</span><br>';
			$ret += 40;
			$pa['mult_words_fdmgbs'] = \attack\add_format(40, $pa['mult_words_fdmgbs']);
		}
		return $ret;
	}
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		if (($pa['bskill']==85) && \skillbase\skill_query(225,$pa))
		{
			$clv = (int)\skillbase\skill_getvalue(85,'lvl',$pa);
			if ($clv >= 1) $acount = 2;
			else $acount = 1;
			$skill85_expup = 0;
			$skill85_wup = 0;
			for ($i=0;$i<$acount;$i++)
			{
				if(rand(0,2) < 2) $skill85_expup += 1;
				if(rand(0,2) < 2) $skill85_wup += 1;
			}
			if ($skill85_expup) \lvlctl\getexp($skill85_expup,$pa);
			if ($skill85_wup)
			{
				eval(import_module('weapon'));
				$pa[$skillinfo[$pa['wep_kind']]] += $skill85_wup;
			}
			if ($active && (!empty($skill85_expup) || !empty($skill85_wup)))
			{
				eval(import_module('logger'));
				$log .= '<span class="yellow b">「神启」使你额外获得了';
				if ($skill85_expup) $log .= $skill85_expup.'点经验值';
				if ($skill85_wup)
				{
					if ($skill85_expup) $log .= '和';
					$log .= $skill85_wup.'点熟练度';
				}
				$log .= '！</span><br>';
			}
		}
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (($pa['bskill']==85) && \skillbase\skill_query(225,$pa))
		{
			$clv = (int)\skillbase\skill_getvalue(85,'lvl',$pa);
			if ($clv >= 1) $acount = 3;
			else $acount = 2;
			$skill85_expup = 0;
			$skill85_wup = 0;
			for ($i=0;$i<$acount;$i++)
			{
				if(rand(0,2) < 2) $skill85_expup += 1;
				if(rand(0,2) < 2) $skill85_wup += 1;
			}
			if ($skill85_expup) \lvlctl\getexp($skill85_expup,$pa);
			if ($skill85_wup)
			{
				eval(import_module('weapon'));
				$pa[$skillinfo[$pa['wep_kind']]] += $skill85_wup;
			}
			if ($active && (!empty($skill85_expup) || !empty($skill85_wup)))
			{
				eval(import_module('logger'));
				$log .= '<span class="yellow b">击杀敌人使你额外获得了';
				if ($skill85_expup) $log .= $skill85_expup.'点经验值';
				if ($skill85_wup)
				{
					if ($skill85_expup) $log .= '和';
					$log .= $skill85_wup.'点熟练度';
				}
				$log .= '！</span><br>';
			}
		}
		$chprocess($pa,$pd,$active);
	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill85') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「神启」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}

}

?>