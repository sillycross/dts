<?php

namespace lvlctl
{
	global $lvuphp, $lvupatt, $lvupdef, $lvupskill, $lvupsp, $lvupspref, $lvupskpt, $sklog;
	
	function init() {}
	
	function getexp($v, &$pa = NULL)	//$pa为NULL时候代表当前玩家获得经验
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$flag=$r=0;
		if ($pa === NULL) 
		{
			\player\update_sdata();
			$flag=1;
			$pa = &$sdata;
		}
		$r=checklvlup($v, $pa);
		if ($flag) 
		{
			\player\player_save($sdata);
			\player\load_playerdata($sdata);
		}
		return $r;
	}
	
	function calc_upexp($l){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','lvlctl'));
		return (int)round(($l * 2 + 1) * $baseexp);
	}
	
	function calc_uplv($exp, $exp0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','lvlctl'));
		return 1 + (int) floor(($exp - $exp0) / $baseexp / 2 );
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
	
	function get_lvllimit(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if(strpos($db_player_structure_types['lvl'],'tinyint')===0) return 250;
		else return 65000;
	}
	
	function checklvlup($v, &$pa) //这个函数不应该从外部直接调用
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','lvlctl'));
		$up_exp_temp = calc_upexp($pa['lvl']);
		$lvllimit = get_lvllimit($pa);
		$upflag = 1;
		if ($pa['lvl'] < $lvllimit) 
		{
			//等级没达到满级才会增加经验值
			$pa['exp']+=$v;
			$upflag = 1;
			if($pa['exp'] >= $up_exp_temp){
				//升级判断
				$lvup = calc_uplv($pa['exp'],  $up_exp_temp);
				//$lvup = 1 + floor ( ($pa['exp'] - $up_exp_temp) / $baseexp / 2 );
				$lvup = $lvup > $lvllimit - $pa['lvl'] ? $lvllimit - $pa['lvl'] : $lvup;
				
				$lvuphp = $lvupatt = $lvupdef = $lvupskill = $lvupsp = $lvupspref = $lvupskpt = 0; $sklog = '';
				for($i = 0; $i < $lvup; $i += 1) 
				{
					if ($pa['lvl'] < $lvllimit) lvlup($pa);
				}
				$up_exp_temp = calc_upexp($pa['lvl']);
				//$up_exp_temp = round ( (2 * $pa['lvl'] + 1) * $baseexp );
				
				if ($pa['lvl'] >= $lvllimit) {
					$pa['lvl'] = $lvllimit;
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
				
				if ($pa['sp'] >= $pa['msp']) $lvupspref = 0;
				elseif ($pa['sp']+$lvupspref >= $pa['msp'])
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
				$tmp_log = "<span class=\"yellow b\">你升了{$lvup}级！生命上限+{$lvuphp}，体力上限+{$lvupsp}，攻击+{$lvupatt}，防御+{$lvupdef}，".(!empty($lvupspref) ? "体力恢复了{$lvupspref}{$sklog}，" : '')."获得了{$lvupskpt}点技能点！</span><br>";
				if ($pa['pid'] === $pid) {
					$log .= $tmp_log;
				} elseif (!$pa['type']) {
					\logger\logsave ( $pa['pid'], $now, $tmp_log, 's');
				}
			}
		} elseif ($pa['lvl'] >= $lvllimit) {
			$upflag = 0;
			$pa['lvl'] = $lvllimit;
			$pa['exp'] = $up_exp_temp;
		}
		//echo $upflag;
		return $upflag;
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
		$upexp = calc_upexp($lvl);
		//$upexp = round(($lvl*$baseexp)+(($lvl+1)*$baseexp));
		$lvlupexp = $upexp - $exp;
		$chprocess();
	}
}

?>
