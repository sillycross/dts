<?php

namespace skill265
{
	$skill265phyup = 80;//物伤加成
	$skill265htrup = 20;//命中率加成
	$skill265prate = 90;//贯穿触发概率
	
	$ragecost=95;
	
	$wep_skillkind_req = 'wg';
	
	$alternate_skillno265 = 205;//互斥技能编号
	$unlock_lvl265 = 17;//解锁等级
	
	function init() 
	{
		define('MOD_SKILL265_INFO','club;battle;upgrade;');
		eval(import_module('clubbase','wep_j'));
		$clubskillname[265] = '穿杨';
		$wj_allowed_bskill[] = 265;
	}
	
	function acquire265(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(265,'unlocked','0',$pa);	//是否已经被解锁
	}
	
	function unlock265(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(265,'unlocked','1',$pa);
	}
	
	function lost265(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked265(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\clubbase\skill_check_unlocked_state(265, $pa) > 0) return 0;
		else return 1;
	}
	
	function get_rage_cost265(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill265'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=265) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(265,$pa) || !check_unlocked265($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost265($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,265) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「穿杨」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「穿杨」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill265', $pa['name'], $pd['name'] );
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
	
	function upgrade265()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill265','player','logger','clubbase'));
		if (!\skillbase\skill_query(265))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		if (\skillbase\skill_getvalue(265,'unlocked') > 0)
		{
			$log .= '你已经选择了这个技能！<br>';
			return;
		}
		if(\clubbase\skill_check_unlocked_state(265) & 4)
		{
			$log .= '你已经选择了互斥的技能！<br>';
			return;
		}

		\skillbase\skill_setvalue(265,'unlocked',1);
		
		$log.='技能「'.$clubskillname[265].'」选择成功。<br>';
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (isset($pa['bskill']) && $pa['bskill']==265) 
		{
			eval(import_module('logger','skill265'));
			$log .= \battle\battlelog_parser($pa, $pd, $active, '<span class="yellow b">「穿杨」使<:pa_name:>造成的物理伤害提高了'.$skill265phyup.'%！</span><br>');
			$r=Array(1+$skill265phyup/100);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}

	function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (isset($pa['bskill']) && $pa['bskill']==265) 
		{
			eval(import_module('skill265'));
//			eval(import_module('logger'));
//			$log .= \battle\battlelog_parser($pa, $pd, $active, '<span class="yellow b">「穿杨」使<:pa_name:>的命中率提升了20%！</span><br>');
			$r = 1+$skill265htrup/100;
		}
		return $chprocess($pa, $pd, $active)*$r;
	}
	
	//射程+1
	function get_weapon_range(&$pa, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $active);
		if (isset($pa['bskill']) && $pa['bskill']==265) 
		{
			$ret += 1;
		}
		return $ret;
	}
	
	//带贯穿属性
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (isset($pa['bskill']) && $pa['bskill']==265) 
		{
			array_push($ret,'n');
		}
		return $ret;
	}
	
	//无视连击属性
	function get_ex_attack_array(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (isset($pa['bskill']) && $pa['bskill']==265) 
		{
			$ret = array_diff($ret, array('r'));
		}
		return $ret;
	}
	
	//贯穿触发率升到90%
	function get_ex_pierce_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (isset($pa['bskill']) && $pa['bskill']==265) {
			eval(import_module('skill265'));
			$ret = $skill265prate;
		}
		return $ret;
	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill265') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「穿杨」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
