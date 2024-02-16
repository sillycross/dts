<?php

namespace skill90
{
	$ragecost = 30;
	$sscost = 10;
	
	function init()
	{
		define('MOD_SKILL90_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[90] = '安魂';
	}
	
	function acquire90(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost90(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked90(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=5;
	}
	
	function get_rage_cost90(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill90'));
		return $ragecost;
	}
	
	function check_battle_skill_unactivatable(&$ldata,&$edata,$skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($ldata,$edata,$skillno);
		if(90 == $skillno && 3 == $ret){
			eval(import_module('skill90'));
			if($ldata['ss'] >= $sscost) $ret = 0;
			else $ret = 10;
		}
		return $ret;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=90) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(90,$pa) || !check_unlocked90($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost90($pa);
			if ($pa['rage']>=$rcost)
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「安魂」！</span><br>";
				else $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「安魂」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill90', $pa['name'], $pd['name'] );
			}elseif (!\clubbase\check_battle_skill_unactivatable($pa,$pd,90)){
				eval(import_module('logger','skill90'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「安魂」！</span><br>";
				else $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「安魂」！</span><br>";
				$pa['ss']-=$sscost;
				addnews ( 0, 'bskill90', $pa['name'], $pd['name'] );
			}else
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
	
	function get_skill90_extra_dmg_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill90','player','logger'));
		$exdmggain = 30;
		//每个临时增益+5%属伤
		if (\skillbase\skill_query(182, $pa))
		{
			$skey = \skillbase\skill_getvalue(182, 'skey', $pa);
			if (!empty($skey))
			{
				$lskey = explode('_', $skey);
				$exdmggain += 5 * count($lskey);
			}
		}
		return $exdmggain;
	}
	
	function calculate_ex_attack_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if ($pa['bskill']==90)
		{
			eval(import_module('logger'));
			$skill90_exdmggain = get_skill90_extra_dmg_gain($pa, $pd, $active);
			if ($active)
				$log .= "<span class=\"lime b\">「安魂」使你造成的属性伤害增加了{$skill90_exdmggain}%！</span><br>";
			else  $log .= "<span class=\"lime b\">「安魂」使敌人造成的属性伤害增加了{$skill90_exdmggain}%！</span><br>";
			$r[] = 1 + $skill90_exdmggain / 100;
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill90') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「安魂」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>