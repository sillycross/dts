<?php

namespace skill215
{

	$ragecost=40;
	
	$wep_skillkind_req = 'wd';
	
	function init() 
	{
		define('MOD_SKILL215_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[215] = '高能';
	}
	
	function acquire215(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost215(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked215(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=6;
	}
	
	function get_rage_cost215(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill215'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=215) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(215,$pa) || !check_unlocked215($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost215($pa);
			if (!\clubbase\check_battle_skill_unactivatable($pa,$pd,215))
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「高能」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「高能」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill215', $pa['name'], $pd['name'] );
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
	
	function ex_attack_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']==215) 
		{
			$ex_att_array = \attrbase\get_ex_attack_array($pa, $pd, $active);
			if (\attrbase\check_in_itmsk('d', $ex_att_array))
			{
				eval(import_module('logger'));
				if ($active)
					$log.='<span class="yellow b">「高能」使你造成的爆炸伤害不受影响！</span><br>';
				else  $log.='<span class="yellow b">「高能」使敌人造成的爆炸伤害不受影响！</span><br>';
			}
		}
		$chprocess($pa, $pd, $active);
	}
	
	//计算完爆炸属性伤害基本值后，记录这个基本值
	function calculate_ex_single_original_dmg(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $key);
		if ( $key=='d' && $pa['bskill']==215 ) 
		{
			$pa['skill215_o_dmg'] = $ret;
		}
		return $ret;
	}
	
	//爆炸属性伤害变化阶段，把伤害变为基本值
	function calculate_ex_single_dmg_change(&$pa, &$pd, $active, $key, $edmg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $key, $edmg);
		if ( $key=='d' && $pa['bskill']==215 && !empty($pa['skill215_o_dmg']) && $ret != $pa['skill215_o_dmg']) 
		{
			eval(import_module('logger'));
			if($ret < $pa['skill215_o_dmg']) $log .= '<span class="cyan b">但是，爆炸伤害不受影响！</span>';
			$ret = round($pa['skill215_o_dmg']);
			unset($pa['skill215_o_dmg']);
		}
		return $ret;
	}
	
//	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if (($key=='d')&&($pa['bskill']==215)) 
//		{
//			return 1;
//		}
//		return $chprocess($pa, $pd, $active, $key);
//	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill215') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「高能」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
