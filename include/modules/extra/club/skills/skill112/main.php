<?php

namespace skill112
{
	$ragecost = 50;
	$skill112_tempskill_time = 300;
	
	function init()
	{
		define('MOD_SKILL112_INFO','club;battle;locked;');
		eval(import_module('clubbase'));
		$clubskillname[112] = '通灵';
	}
	
	function acquire112(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost112(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked112(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=15;
	}
	
	function get_rage_cost112(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill112'));
		return $ragecost;
	}
	
	function check_battle_skill_unactivatable(&$ldata,&$edata,$skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($ldata,$edata,$skillno);
		if(112 == $skillno && 0 == $ret){
			$sanity = (int)\skillbase\skill_getvalue(107,'sanity',$ldata);
			if($sanity <= 0) $ret = 10;
		}
		return $ret;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=112) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(112,$pa) || !check_unlocked112($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost112($pa);
			if (!\clubbase\check_battle_skill_unactivatable($pa,$pd,112))
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「通灵」！</span><br>";
				else $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「通灵」！</span><br>";
				$pa['rage']-=$rcost;
				if (rand(0,99) < 30)
				{
					$pa['skill112_sanflag'] = 1;
					\skill107\skill107_lose_sanity(1, $pa);
				}
				addnews ( 0, 'bskill112', $pa['name'], $pd['name'] );
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
	
	function check_physical_def_attr(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']==112) return array();
		return $chprocess($pa, $pd, $active);
	}
	
	function check_ex_single_dmg_def_attr(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']==112) return 1;
		return $chprocess($pa, $pd, $active, $key);
	}
	
	function check_ex_rapid_def_exists(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']==112) return 0;
		return $chprocess($pa, $pd, $active);
	}	
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if ($pa['bskill']==112)
		{
			eval(import_module('logger'));
			if ($active) $log .= '<span class="lime b">你的攻击渗透了敌人的灵魂！</span><br>';
			else $log .= '<span class="lime b">敌人的攻击渗透了你的灵魂！</span><br>';
			$r = array(1.3);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']==112)
		{
			if (!isset($pa['skill112_sanflag'])) \skill107\skill107_lose_sanity(1, $pa);
			$pd_skills = \skillbase\get_acquired_skill_array($pd);
			$pd_skills = array_diff($pd_skills, array(1,2,3,4,5,6,7,8,9,10,11,12,55,56,72,81,106,242,460,512));
			if ($pd['type'] == 0) $filter_info_arr = array('hidden', 'achievement', 'debuff', 'feature', 'card');
			else $filter_info_arr = array('hidden', 'achievement', 'debuff', 'feature');
			foreach($pd_skills as $k => $skid)
			{
				$const = constant('MOD_SKILL'.$skid.'_INFO');
				foreach ($filter_info_arr as $v) {
					if(strpos($const, $v)!==false) {
						unset($pd_skills[$k]);
						break;
					}
				}
			}
			if (!empty($pd_skills))
			{
				$newskillid = array_randompick($pd_skills, 1);
				\skillbase\skill_acquire($newskillid, $pa);
				foreach($pd['parameter_list'] as $pk => $pv)
				{
					list($sid,$skey) = explode('_',$pk,2);
					if ($sid == $newskillid) \skillbase\skill_setvalue($newskillid, $skey, $pv, $pa);
				}
				eval(import_module('sys','skill112','logger'));
				if ($active) $log .= "<span class=\"cyan b\">你从敌人的灵魂中汲取了新的知识！</span><br>";
				\skillbase\skill_setvalue($newskillid, 'tsk_expire', $now + $skill112_tempskill_time);
			}
			else
			{
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"yellow b\">敌人的灵魂中似乎没有什么有价值的知识……</span><br>";
			}
		}
		$chprocess($pa,$pd,$active);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill112')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「通灵」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>