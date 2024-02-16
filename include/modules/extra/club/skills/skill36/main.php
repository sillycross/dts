<?php

namespace skill36
{
	//怒气消耗
	$ragecost = 25; 
	
	$wep_skillkind_req = 'wp';
	
	function init() 
	{
		define('MOD_SKILL36_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[36] = '偷袭';
	}
	
	function acquire36(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost36(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked36(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=3;
	}
	
	function get_rage_cost36(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill36'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=36) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(36,$pa) || !check_unlocked36($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost36($pa);
			if (!\clubbase\check_battle_skill_unactivatable($pa,$pd,36))
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「偷袭」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「偷袭」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill36', $pa['name'], $pd['name'] );
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
	
	//必定触发技能35猛击
	function calculate_skill35_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=36) return $chprocess($pa, $pd, $active);
		return 100;
	}
	
	//若要接管此函数，请阅读base\battle\battle.php里的注释，并加以判断
	function check_can_counter(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//注意这里判定的是$pa能否反击$pd，因此应该判定$pd是否发动了偷袭
		if ($pd['bskill']!=36) return $chprocess($pa, $pd, $active);
		return 0;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill36') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「偷袭」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
