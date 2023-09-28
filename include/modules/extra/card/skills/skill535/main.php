<?php

namespace skill535
{
	$get_money535_rate = 10;//每秒获得钱的概率
	$get_money535_amount = 1;//每秒获得的钱数
	
	function init() 
	{
		define('MOD_SKILL535_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[535] = '挖矿';
	}
	
	function acquire535(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost535(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked535(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//只要有这个技能，就无法恢复HP和SP
	//现在直接被跳过了
//	function calculate_rest_upsp($rtime)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if(\skillbase\skill_query(535)) return 0;
//		return $chprocess($rtime);
//	}
//	
//	function calculate_rest_uphp($rtime)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if(\skillbase\skill_query(535)) return 0;
//		return $chprocess($rtime);
//	}
	
	function get_money535($rtime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(535) && !empty($rtime)) {
			eval(import_module('sys','player','skill535','logger'));
			//30秒以内的部分是认真算的，30秒以外的部分算期望
			if($rtime <= 30) {
				$r1 = $rtime; $r2 = 0;
			}else{
				$r1 = 30; $r2 = $rtime - 30;
			}
			$moneyup = calc_money535($r1);
			$moneyup += round($r2 * $get_money535_amount * $get_money535_rate / 100);
			$log .= '尽管疲惫而疼痛，你却被迫着调动全部脑力，努力计算着庞杂的公式。';
			if(!empty($moneyup)){
				$log .= '<br>艰苦的运算后，你得到了少得可怜的<span class="yellow b">'.$moneyup.'元</span>作为补偿。<br>';
				$money += $moneyup;
			}else{
				$log .= '<br>但是你什么都没有算出来。<br>';
			}
		}
	}
	
	//确实地计算每一秒的概率
	function calc_money535($rtime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = 0;
		if(!empty($rtime)) {
			eval(import_module('skill535'));
			$rtime = (int)$rtime;
			for($i=0;$i<$rtime;$i++){
				if(rand(0,99) < $get_money535_rate) $ret += $get_money535_amount;
			}
		}
		return $ret;
	}
	
	function rest($restcommand) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(535)) {
			eval(import_module('sys','player'));
			$resttime = $now - $endtime;
			get_money535($resttime);
			return;
		}
		$chprocess($restcommand);
	}
}

?>