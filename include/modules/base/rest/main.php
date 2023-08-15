<?php

namespace rest
{
	function init() {}
	
	function calculate_rest_upsp($rtime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','rest'));
		$upsp = round ( $msp * $rtime / $rest_sleep_time / 100 );
		return $upsp;
	}
			
	function calculate_rest_uphp($rtime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','rest'));
		$uphp = round ( $mhp * $rtime / $rest_heal_time / 100 );
		if (strpos ( $inf, 'b' ) !== false) {
			$uphp = round ( $uphp / 2 );
		}
		return $uphp;
	}
	
	function get_wound_recover_time()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','rest'));
		return $rest_recover_time;
	}
	
	function rest($restcommand) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','rest'));
		
		$resttime = $now - $endtime;
		$endtime = $now;
		
		if ($state == 1 || $state == 3) {
			$oldsp = $sp;
			$upsp = calculate_rest_upsp($resttime);
			$sp += $upsp; $sp = min($sp, $msp);
			$upsp = $sp - $oldsp;
			$upsp=max(0,$upsp);
			$log .= "你的体力恢复了<span class=\"yellow b\">$upsp</span>点。";
		} 
		
		if ($state == 2 || $state == 3) {
			$oldhp = $hp;
			$uphp = calculate_rest_uphp($resttime);
			$hp += $uphp; $hp = min($hp, $mhp);
			$uphp = $hp - $oldhp;
			$uphp=max(0,$uphp);
			$log .= "你的生命恢复了<span class=\"yellow b\">$uphp</span>点。";
		} 
		
		$log .= '<br>';
		
		if ($state == 3 && defined('MOD_WOUND'))
		{
			eval(import_module('wound'));
			$refintv = get_wound_recover_time();
			if ($inf && $resttime > $refintv) 
			{
				while ($inf!='')
				{
					$log .= "<span class=\"yellow b\">你从{$infname[$inf[0]]}状态中恢复了！</span><br>";
					\wound\heal_inf($inf[0]);
				}
			}
			elseif ($inf)
			{
				$log .= "也许是时间不够吧……你没有治好任何异常状态。<br>";
			}
		} 
		
		if ($restcommand != 'rest') {
			$state = 0;
			$endtime = $now;
			$mode = 'command';
		}
		return;
	}
	
	function init_rest_timing(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		//静养计时
		if(!isset($uip['timing'])) $uip['timing'] = array();
		$uip['timing']['rest_timing'] = array(
			'on' => true,
			'mode' => 1,
			'timing' => 0,
			'timing_r' => 0,
			'format' => 'mm:ss'
		);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		
		if ($mode == 'command' && ($command=='rest1' || $command=='rest2' || $command=='rest3'))
		{
			eval(import_module('rest'));
			if($command=='rest3' && !in_array($pls,$rest_hospital_list)){
				$log .= '<span class="yellow b">你所在的位置并非医院，不能静养！</span><br>';
			}else{
				$state = substr($command,4,1);
				$mode = 'rest';
				$log .= '你开始了'.$restinfo[$state].'…';
			}
			return;
		}
		
		if($mode == 'rest')
		{
			rest($command);
			return;
		}
		
		$chprocess();
	}
}

?>
