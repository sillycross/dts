<?php

namespace skill253
{
	$ragecost=25;
	
	function init() 
	{
		define('MOD_SKILL253_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[253] = '天威';
	}
	
	function acquire253(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost253(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked253(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=5;
	}
	
	function get_rage_cost253(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill253'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=253) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(253,$pa) || !check_unlocked253($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost253($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,253) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「天威」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「天威」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill253', $pa['name'], $pd['name'] );
				$pd['old_hp']=$pd['hp'];	//记录战斗开始时的hp，用于判定返还怒气
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
	
	function get_skill(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (empty($pa['bskill']) || $pa['bskill']!=253) return $chprocess($pa,$pd,$active);
		$r = min(220,round($pa['lvl']*($pa['rage']+get_rage_cost253())/6));
		eval(import_module('logger'));
		if ($active)
			$log.='<span class="yellow b">「天威」使你的熟练度暂时增加了'.$r.'点！</span><br>';
		else  $log.='<span class="yellow b">「天威」使敌人的熟练度暂时增加了'.$r.'点！</span><br>';
		return $chprocess($pa,$pd,$active)+$r;
	}
	
	function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$ret = $chprocess($pa,$pd);
		
		if (!empty($pa['bskill']) && $pa['bskill']==253 && $pa['dmg_dealt'] <= $pd['old_hp']*1.5)
		{
			$r=get_rage_cost253();
			eval(import_module('logger'));
			$log.='「天威」击杀效果触发，返还了<span class="yellow b">'.$r.'</span>点怒气！<br>';
			$pa['rage']+=$r;
			if ($pa['rage']>100) $pa['rage']=100;
		}
		return $ret;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill253') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「天威」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
