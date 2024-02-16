<?php

namespace skill49
{
	//怒气消耗
	$ragecost = 70; 
	
	$wep_skillkind_req = 'wc';
	
	//解锁消耗
	$unlockcost = 5;
	
	function init() 
	{
		define('MOD_SKILL49_INFO','club;battle;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[49] = '潜能';
	}
	
	function acquire49(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(49,'u','0',$pa);
	}
	
	function lost49(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function check_unlocked49(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$z=(int)\skillbase\skill_getvalue(49,'u',$pa);
		return ($z==1);
	}
	
	function get_rage_cost49(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill49'));
		return $ragecost;
	}
	
	function upgrade49()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill49','player','logger'));
		if (!\skillbase\skill_query(49))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		if (check_unlocked49($sdata))
		{
			$log .= '你已经解锁了这个技能。<br>';
			return;
		}
		if ($skillpoint<$unlockcost)
		{
			$log.='技能点不足。<br>';
			return;
		}
		$skillpoint -= $unlockcost;
		\skillbase\skill_setvalue(49,'u',1);
		$log.='解锁成功。<br>';
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=49) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(49,$pa) || !check_unlocked49($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost49($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,49) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「潜能」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「潜能」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill49', $pa['name'], $pd['name'] );
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
	
	//必中
	function get_hitrate_change(&$pa,&$pd,$active,$hitrate)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=49) return $chprocess($pa, $pd, $active,$hitrate);
		return 10000;
	}
	
	//连击无衰减
	function get_rapid_accuracy_loss(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=49) return $chprocess($pa, $pd, $active);
		return 10.0;
	}
	
	//物理伤害×1.2
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ($pa['bskill']==49) 
		{
			eval(import_module('logger'));
			$r=Array(1.2);
			if ($active)
				$log.='<span class="yellow b">你爆发潜能打出了致命一击！</span><br>';
			else  $log.='<span class="yellow b">敌人爆发潜能打出了致命一击！</span><br>';
		}
		return array_merge($r,$chprocess($pa, $pd, $active));
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill49') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「潜能」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
