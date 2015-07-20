<?php

namespace lvlctl
{
	global $lvuphp, $lvupatt, $lvupdef, $lvupskill, $lvupsp, $lvupspref, $lvupskpt, $sklog;
	
	function init() {}
	
	function getexp($v, &$pa = NULL)	//$pa为NULL时候代表当前玩家获得经验
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$flag=0;
		if ($pa === NULL) 
		{
			\player\update_sdata();
			$flag=1;
			$pa = &$sdata;
		}
		checklvlup($v, $pa);
		if ($flag) 
		{
			\player\load_playerdata($sdata);
		}
	}
		
	function lvlup(&$pa)	//这个函数不应该从外部直接调用
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','lvlctl'));
		$lvuphp += rand ( 9, 11 );
		$lvupsp += rand( 4, 6 );
		$lvupatt += rand ( 2, 4 );
		$lvupdef += rand ( 3, 5 );
				
		//if ($skname == 'all') {
		//$lvupskill += rand ( 2, 4 );
		//} elseif ($skname == 'wd' || $skname == 'wf') {
		//$lvupskill += rand ( 3, 5 );
		//}elseif($skname){
		//$lvupskill += rand ( 4, 6 );
		//}
		$lvupspref += round($pa['msp'] * 0.1);	
		
		$lvupskpt ++;
		$pa['lvl']++;
	}
	
	function checklvlup($v, &$pa) //这个函数不应该从外部直接调用
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','lvlctl'));
	
		$up_exp_temp = round ( (2 * $pa['lvl'] + 1) * $baseexp );
		$pa['exp']+=$v;
		if ($pa['exp'] >= $up_exp_temp && $pa['lvl'] < 255) 
		{
			//升级判断
			$lvup = 1 + floor ( ($pa['exp'] - $up_exp_temp) / $baseexp / 2 );
			$lvup = $lvup > 255 - $pa['lvl'] ? 255 - $pa['lvl'] : $lvup;
			
			$lvuphp = $lvupatt = $lvupdef = $lvupskill = $lvupsp = $lvupspref = $lvupskpt = 0; $sklog = '';
			for($i = 0; $i < $lvup; $i += 1) 
			{
				if ($pa['lvl'] < 255) lvlup($pa);
			}
			$up_exp_temp = round ( (2 * $pa['lvl'] + 1) * $baseexp );
			
			if ($pa['lvl'] >= 255) {
				$pa['lvl'] = 255;
				$pa['exp'] = $up_exp_temp;
			}
			$pa['upexp'] = $up_exp_temp;
			$pa['hp'] += $lvuphp;
			$pa['mhp'] += $lvuphp;
			$pa['sp'] += $lvupsp;
			$pa['msp'] += $lvupsp;
			$pa['att'] += $lvupatt;
			$pa['def'] += $lvupdef;
			/*
			if ($skname == 'all') {
				${$perfix . 'wp'} += $lvupskill;
				${$perfix . 'wk'} += $lvupskill;
				${$perfix . 'wg'} += $lvupskill;
				${$perfix . 'wc'} += $lvupskill;
				${$perfix . 'wd'} += $lvupskill;
				${$perfix . 'wf'} += $lvupskill;
			} elseif ($skname) {
				${$perfix . $skname} += $lvupskill;
			}
			*/
			
			if ($pa['sp']+$lvupspref >= $pa['msp']) 
			{
				$lvupspref = $pa['msp'] - $pa['sp'];
			}
			$pa['sp'] += $lvupspref;
			$pa['skillpoint']+= $lvupskpt;
			/*
			if ($skname) {
				$sklog = "，{$sklanginfo[$skname]}+{$lvupskill}";
			}
			*/
			if ($pa['pid'] === $pid) {
				$log .= "<span class=\"yellow\">你升了{$lvup}级！生命上限+{$lvuphp}，体力上限+{$lvupsp}，攻击+{$lvupatt}，防御+{$lvupdef}，体力恢复了{$lvupspref}{$sklog}，获得了{$lvupskpt}点技能点！</span><br>";
			} elseif (!$pa['type']) {
				$w_log = "<span class=\"yellow\">你升了{$lvup}级！生命上限+{$lvuphp}，体力上限+{$lvupsp}，攻击+{$lvupatt}，防御+{$lvupdef}，体力恢复了{$lvupspref}{$sklog}，获得了{$lvupskpt}点技能点！</span><br>";
				\logger\logsave ( $pa['pid'], $now, $w_log,'s');
			}
		} elseif ($pa['lvl'] >= 255) {
			$pa['lvl'] = 255;
			$pa['exp'] = $up_exp_temp;
		}
		return;
	}
	
	function load_playerdata($pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (isset($pdata['upexp'])) $upexp=$pdata['upexp'];
		$chprocess($pdata);
	}
	
	function init_playerdata()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','lvlctl'));
		$upexp = round(($lvl*$baseexp)+(($lvl+1)*$baseexp));
		$lvlupexp = $upexp - $exp;
		$chprocess();
	}
		
}

?>
