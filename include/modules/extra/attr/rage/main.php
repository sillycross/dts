<?php

namespace rage
{
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['HR']='怒气增加';
		$itemspkinfo['c']='重击辅助';
	}
	
	//静养获得怒气
	function calculate_rest_uprage($rtime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','rest','rage'));
		$uprage = round ( $max_rage * $rtime / $rest_heal_time / 200 );
		if (strpos ( $inf, 'h' ) !== false) {//脑袋受伤不容易愤怒（
			$uprage = round ( $uprage / 2 );
		}
		return $uprage;
	}
	
	function rest($restcommand) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','rest','rage'));
		$resttime = $now - $endtime;
		//$endtime = $now; //不能在这里就把$endtime覆盖掉
		
		$uprage = calculate_rest_uprage($resttime);
		$rage0 = $rage;
		$rage += $uprage; 
		$rage = min($rage, $max_rage);
		$uprage = $rage - $rage0;
		$log .= "你的怒气增加了<span class=\"yellow\">$uprage</span>点。";
		
		$chprocess($restcommand);
	}
	
	//计算攻击怒气获得
	function calculate_attack_rage_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$rageup = round ( ($pa['lvl'] - $pd['lvl']) / 3 );
		$rageup = $rageup > 0 ? $rageup : 1;
		$rageup = $rageup > 15 ? 15 : $rageup;//一次不能获得超过15点怒气
		return $rageup;
	}
	
	function strike_finish(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('rage'));
		if ($pa['is_hit'])	//被命中的玩家获得怒气
		{
			$pd['rage']+=calculate_attack_rage_gain($pa, $pd, $active);
			$pd['rage']=min($pd['rage'],$max_rage);
			if (\attrbase\check_itmsk('c',$pa)){
				$pa['rage']++;
				$pa['rage']=min($pa['rage'],$max_rage);
			}
		}else //miss的玩家获得怒气 攻击者等级越高则越生气（“我怎么连菜鸟都打不过”）
		{
			$pa['rage']+=calculate_attack_rage_gain($pa, $pd, $active);
			$pa['rage']=min($pa['rage'],$max_rage);
		}
		$chprocess($pa,$pd, $active);
	}
	
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','wound','logger','rage'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'HR' ) === 0) 
		{
			$rageup = min($max_rage-$rage,$itme);
			if ($rageup<=0)
			{
				$log.='你已经出离愤怒了，动怒伤肝，还是歇歇吧！<br>';
				return;
			}
			$rage += $rageup;
			$log.="你吃了一口{$itm}，顿时感觉心中充满了愤怒。你的怒气值增加了<span class=\"yellow\">{$rageup}</span>点！<br>";
			\itemmain\itms_reduce($theitem);
			return;
		}
		
		$chprocess($theitem);
	}
	
	function attack_prepare(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['original_rage']=$pa['rage'];
		$chprocess($pa, $pd, $active);
	}
	
	function attack_finish(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('rage'));
		if (\attrbase\check_itmsk('c',$pa))
		{
			$lost_rage = $pa['original_rage']-$pa['rage'];
			if ($lost_rage > 0)
			{
				$payback_rage = round($lost_rage/10);
				$pa['rage']+=$payback_rage;
				if ($pa['rage']>$max_rage) $pa['rage']=$max_rage;
			}
		}
		$chprocess($pa, $pd, $active);
	}
}

?>