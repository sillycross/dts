<?php

namespace skill228
{
	$skill228_skillpoint_req = 5;
	$stuntime228 = 1000;
	
	function init() 
	{
		define('MOD_SKILL228_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[228] = '神击';
	}
	
	function acquire228(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost228(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked228(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=21;
	}
	
	function get_rage_cost228(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 100;
	}
	
	//怒气不足但是有技能点时也可发动
	function check_battle_skill_unactivatable(&$ldata,&$edata,$skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($ldata,$edata,$skillno);
		if(228 == $skillno && 3 == $ret){
			eval(import_module('skill228'));
			if($ldata['skillpoint'] >= $skill228_skillpoint_req) $ret = 0;//有技能点，返回0
			else $ret = 6;//没技能点返回特殊值6
		}
		return $ret;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=228) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(228,$pa) || !check_unlocked228($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost228($pa);
			if ($pa['rage']>=$rcost)
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「神击」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「神击」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill228', $pa['name'], $pd['name'] );
			}elseif (!\clubbase\check_battle_skill_unactivatable($pa,$pd,228)){
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「神击」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「神击」！</span><br>";
				$pa['skillpoint']-=5;
				addnews ( 0, 'bskill228', $pa['name'], $pd['name'] );
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
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ($pa['bskill']==228) 
		{
			eval(import_module('logger','skill228'));
			if ($active)
				$log.='<span class="lime b">你有如天神下凡，对敌人打出雷霆一击！</span><span class="cyan b">敌人被你击晕了！</span><br>';
			else  $log.='<span class="lime b">敌人有如天神下凡，对你打出雷霆一击！</span><span class="cyan b">你被敌人击晕了！</span><br>';
			$r=Array(1.6);
			\skill602\set_stun_period($stuntime228,$pd);
			\skill602\send_stun_battle_news($pa['name'],$pd['name']);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill228') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「神击」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
