<?php

namespace rage
{
	
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['HR']='怒气增加';
		$itemspkinfo['c']='集气';
		$itemspkdesc['c']='攻击时额外获得1点怒气；发动战斗技时会返还10%消耗的怒气';
		$itemspkremark['c']='……';
	}
	
	//2024.02.16改为继承player模块的同名函数
	function get_max_rage(&$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('rage'));
		return $max_rage;
	}
	
	//静养获得怒气
	function calculate_rest_rageup($rtime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','rest','rage'));
		$rageup = round ( get_max_rage() * $rtime / $rest_rage_time / 100 );
		if (strpos ( $inf, 'h' ) !== false) {//脑袋受伤不容易愤怒（
			$rageup = round ( $rageup / 2 );
		}
		return $rageup;
	}
	
	function rest($restcommand) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','rest','rage'));
		$resttime = $now - $endtime;
		//$endtime = $now; //不能在这里就把$endtime覆盖掉
		
		$rageup = calculate_rest_rageup($resttime);
		$rageup = get_rage($rageup);
		$log .= "你的怒气增加了<span class=\"yellow b\">$rageup</span>点。";
		
		$chprocess($restcommand);
	}
	
	//计算攻击怒气获得
	function calculate_attack_rage_gain(&$pa, &$pd, $active, $receiver ,$fixed_val=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if('pa' == $receiver) $pa['receive_rage'] = 1;//记录怒气获得者
		else $pd['receive_rage'] = 1;
		$rageup = calculate_attack_rage_gain_base($pa, $pd, $active, $fixed_val);
		//echo $rageup.' ';
		$rageup *= calculate_attack_rage_gain_multiplier($pa, $pd, $active);
		//echo $rageup.' ';
		$rageup = calculate_attack_rage_gain_change($pa, $pd, $active, $rageup);
		//echo $rageup.' ';
		unset($pa['receive_rage'], $pd['receive_rage']);
		return $rageup;
	}
	
	//战斗怒气基础值
	function calculate_attack_rage_gain_base(&$pa, &$pd, $active, $fixed_val=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($fixed_val) $rageup = 1;
		else{
			$rageup = round ( (\weapon\calculate_attack_lvl($pa) - \weapon\calculate_attack_lvl($pd)) / 3 );
			$rageup = $rageup > 0 ? $rageup : 1;
		}
		return $rageup;
	}
	
	//战斗怒气加成值
	function calculate_attack_rage_gain_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//战斗怒气修正值，继承此函数请把$chprocess写在最后
	function calculate_attack_rage_gain_change(&$pa, &$pd, $active, $rageup)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$rageup = $rageup > 15 ? 15 : $rageup;//一次不能获得超过15点怒气
		return $rageup;
	}
	
	function get_rage($rageup, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if(!$pa) $pa = &$sdata;
		$max_rage = get_max_rage();
		if($rageup <= 0 || $pa['rage'] > $max_rage) return 0;
		if($rageup + $pa['rage'] > $max_rage) $rageup = $max_rage - $pa['rage'];
		$pa['rage'] += $rageup;
		return $rageup;
	}
	
	function strike_finish(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('rage'));
		if ($pa['is_hit'])	//被命中的玩家获得怒气
		{
			$rageupd = calculate_attack_rage_gain($pa, $pd, $active, 'pd');
			get_rage($rageupd, $pd);
			if (\attrbase\check_in_itmsk('c', \attrbase\get_ex_attack_array($pa, $pd, $active))){//如果攻击玩家有集气属性，则按基础值1获得
				$rageupa = calculate_attack_rage_gain($pa, $pd, $active, 'pa', 1);
				get_rage($rageupa, $pa);
				//echo '集气获得：'.$rageupa;
			}
		}else //miss的玩家获得怒气 攻击者等级越高则越生气（“我怎么连菜鸟都打不过”）
		{
			$rageupa = calculate_attack_rage_gain($pa, $pd, $active, 'pa');
			get_rage($rageupa, $pa);
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
			$rageup = get_rage($itme);
			if ($rageup<=0)
			{
				$log.='你已经出离愤怒了，动怒伤肝，还是歇歇吧！<br>';
				return;
			}
			$log.="你吃了一口{$itm}，顿时感觉心中充满了愤怒。你的怒气值增加了<span class=\"yellow b\">{$rageup}</span>点！<br>";
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
	
	//集气属性战斗后返还怒气
	function attack_finish(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('rage'));
		if (\attrbase\check_in_itmsk('c', \attrbase\get_ex_attack_array($pa, $pd, $active)))
		{
			$lost_rage = $pa['original_rage']-$pa['rage'];
			if ($lost_rage > 0)
			{
				$payback_rage = round($lost_rage/10);
				get_rage($payback_rage, $pa);
			}
		}
		$chprocess($pa, $pd, $active);
	}
}

?>