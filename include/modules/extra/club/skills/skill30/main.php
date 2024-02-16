<?php

namespace skill30
{
	//怒气消耗
	$ragecost = 30; 
	
	function init() 
	{
		define('MOD_SKILL30_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[30] = '压制';
	}
	
	function acquire30(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost30(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked30(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=7;
	}
	
	function get_rage_cost30(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill30'));
		return $ragecost;
	}
	
	function get_hp_cost30(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$pa) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		return min($pa['hp']-1, round($pa['mhp']*0.15));
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=30) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(30,$pa) || !check_unlocked30($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost30($pa);
			if (!\clubbase\check_battle_skill_unactivatable($pa,$pd,30))
			{
				$hpcost = get_hp_cost30($pa);
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「压制」！</span><br>
						<span class=\"yellow b\">你消耗了<span class=\"red b\">{$hpcost}</span>点生命值，发动了鲁莽的一击！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「压制」！</span><br>
						<span class=\"yellow b\">其消耗了<span class=\"red b\">{$hpcost}</span>点生命值，发动了鲁莽的一击！</span><br>";
				$pa['rage']-=$rcost;
				$pa['hp']-=$hpcost;
				//$pd['hp']-=$hpcost;
				$pa['skill30_hpcost']=$hpcost;
				addnews ( 0, 'bskill30', $pa['name'], $pd['name'] );
			}
			else
			{
				if ($active)
				{
					eval(import_module('logger'));
					$log.='怒气不足。<br>';
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
		if ($pa['bskill']==30 && $pa['is_hit'] && !empty($pa['skill30_hpcost'])) 
		{
			$ret+=$pa['skill30_hpcost'];
			$pa['mult_words_fdmgbs'] = \attack\add_format($pa['skill30_hpcost'], $pa['mult_words_fdmgbs']);
		}
		return $ret;
	}
	
//	function strike_finish(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if ($pa['bskill']!=30) return $chprocess($pa, $pd, $active);
//		if ($pa['dmg_dealt']>0){
//			$pa['dmg_dealt']+=$pa['skill30_hpcost'];
//		}
//		$chprocess($pa, $pd, $active);
//	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill30') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「压制」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
