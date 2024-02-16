<?php

namespace skill208
{

	$ragecost=70;
	
	$wep_skillkind_req = 'wk';
	
	function init() 
	{
		define('MOD_SKILL208_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[208] = '强袭';
	}
	
	function acquire208(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost208(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked208(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=15;
	}
	
	function get_rage_cost208(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill208'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=208) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(208,$pa) || !check_unlocked208($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost208($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,208))
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「强袭」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「强袭」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill208', $pa['name'], $pd['name'] );
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
	
	function check_ex_rapid_def_exists(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret =  $chprocess($pa, $pd, $active);
		//强袭跳过防连判定
		if ($pa['bskill']==208) $ret = 0;
		return $ret;
	}
	
	function check_physical_def_attr(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=208) return $chprocess($pa, $pd, $active);
		return Array();
	}
	
	function check_ex_single_dmg_def_attr(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=208) return $chprocess($pa, $pd, $active,$key);
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ($pa['bskill']==208) 
		{
			eval(import_module('logger'));
			if ($pa['card']==5){
				if ($active)
					$log.='<span class="yellow b">「强袭」使你造成的最终伤害提高了70%！</span><br>';
				else  $log.='<span class="yellow b">「强袭」使敌人造成的最终伤害提高了70%！</span><br>';
				$r=Array(1.7);
			}else{
				if ($active)
					$log.='<span class="yellow b">「强袭」使你造成的最终伤害提高了40%！</span><br>';
				else  $log.='<span class="yellow b">「强袭」使敌人造成的最终伤害提高了40%！</span><br>';
				$r=Array(1.4);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill208') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「强袭」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
