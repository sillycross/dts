<?php

namespace skill38
{
	//怒气消耗
	$ragecost = 85; 
	
	$wepk_req = 'WP';
	
	function init() 
	{
		define('MOD_SKILL38_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[38] = '闷棍';
	}
	
	function acquire38(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost38(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked38(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=11;
	}
	
	function get_rage_cost38(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill38'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=38) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(38,$pa) || !check_unlocked38($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost38($pa);
			if (!\clubbase\check_battle_skill_unactivatable($pa,$pd,38))
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「闷棍」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「闷棍」！</span><br>";
				if (!$pd['type'])
					$pa['skill38_dmg_extra']=max($pd['msp']-$pd['sp'],0);
				else  $pa['skill38_dmg_extra']=0;
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill38', $pa['name'], $pd['name'] );
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
		if ($pa['bskill']!=38) return $chprocess($pa, $pd, $active);
		return 100;
	}
	
	function get_final_dmg_base(&$pa, &$pd, &$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if ($pa['bskill']==38 && $pa['is_hit'] && !empty($pa['skill38_dmg_extra'])) 
		{
			eval(import_module('logger'));
			if ($active)
				$log.="闷棍对体力不支的敌人造成了<span class=\"yellow b\">{$pa['skill38_dmg_extra']}</span>点额外伤害！<br>";
			else  $log.="闷棍对体力不支的你造成了<span class=\"yellow b\">{$pa['skill38_dmg_extra']}</span>点额外伤害！<br>";
			$ret+=$pa['skill38_dmg_extra'];
			$pa['mult_words_fdmgbs'] = \attack\add_format($pa['skill38_dmg_extra'], $pa['mult_words_fdmgbs']);
		}
		return $ret;
	}
	
	//附加伤害
//	function strike_finish(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if ($pa['bskill']!=38) return $chprocess($pa, $pd, $active);
//		eval(import_module('logger'));
//		if ($pa['is_hit'])
//		{
//			if ($active)
//				$log.="闷棍对体力不支的敌人造成了<span class=\"yellow b\">{$pa['skill38_dmg_extra']}</span>点额外伤害！<br>";
//			else  $log.="闷棍对体力不支的你造成了<span class=\"yellow b\">{$pa['skill38_dmg_extra']}</span>点额外伤害！<br>";
//			$pa['dmg_dealt']+=$pa['skill38_dmg_extra'];
//		}
//		$chprocess($pa, $pd, $active);
//	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill38') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「闷棍」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
