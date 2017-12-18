<?php

namespace skill47
{
	//怒气消耗
	$ragecost = 5; 
	
	function init() 
	{
		define('MOD_SKILL47_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[47] = '花雨';
	}
	
	function acquire47(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost47(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked47(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=3;
	}
	
	function get_rage_cost47(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill47'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=47) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(47,$pa) || !check_unlocked47($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost47($pa);
			if ($pa['rage']>=$rcost && $pa['wep_kind']=='C')
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime\">你对{$pd['name']}发动了技能「花雨」！</span><br>";
				else  $log.="<span class=\"lime\">{$pa['name']}对你发动了技能「花雨」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill47', $pa['name'], $pd['name'] );
				
				eval(import_module('ex_dmg_att'));
				$lis = Array('p', 'u', 'i', 'e', 'w');
//				$lis=Array();
//				foreach ($ex_attack_list as $key) if ($key!='f' && $key!='k' && $key!='t' && $key!='d') array_push($lis,$key);
				$pa['skill47_flag']=$lis[rand(0,count($lis)-1)];
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
		if ($pa['bskill']!=47) return $chprocess($pa, $pd, $active);
		eval(import_module('itemmain','logger'));
		$log.='技能「花雨」附加了<span class="yellow">'.$itemspkinfo[$pa['skill47_flag']].'</span>属性伤害！<br>';
		return $chprocess($pa, $pd, $active);
	}
	
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=47) return $chprocess($pa, $pd, $active);
		$r=$chprocess($pa, $pd, $active);
		array_push($r,$pa['skill47_flag']);
		return $r;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill47') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"clan\">{$a}对{$b}发动了技能<span class=\"yellow\">「花雨」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
