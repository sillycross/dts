<?php

namespace skill81
{
	$skill81rate = 50;//可换可不换的情况下更换武器的概率
	
	function init() 
	{
		define('MOD_SKILL81_INFO','club;unique;locked;');
		eval(import_module('clubbase'));
		$clubskillname[81] = '换装';
	}
	
	function acquire81(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost81(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked81(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function check_swapable_items81(&$pa, $swk='W')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = array();
		if(!in_array($swk, array('W','D'))) return $ret;
		
		for($i=1; $i <=6; $i++){
			if($pa['itms'.$i] && strpos($pa['itmk'.$i],$swk)===0) $ret[] = $i;
		}
		return $ret;
	}
	
	//这个函数里，$pa是欲换武器者（NPC）
	//这里的$active与玩家是相反的
	function check_battleswap(&$pa, &$pd, $active, $logflag=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$s_arr = check_swapable_items81($pa);
		if(!$s_arr || !$pa['type']) return;//玩家没效果
		$r1 = \weapon\get_weapon_range($pa, $active);
		$r2 = \weapon\get_weapon_range($pd, 1-$active);
		if(!$r2) return;//爆系无法反击，不换武器
		$flag = 0;//是否必须更换的标记
		if($r1 < $r2) $flag = 1;
		//规则1：更换的武器必须能够反击
		$del_arr = array();
		$flag = 0;
		foreach($s_arr as $si){
			swapitem81($pa, 'wep', $si);
			$r1 = \weapon\get_weapon_range($pa, $active);
			if(!$active && $r1 < $r2) $del_arr[] = $si;
			swapitem81($pa, 'wep', $si);
		}
		
		$s_arr = array_diff($s_arr, $del_arr);
		//echo 'sarr ';var_dump($s_arr);echo '<br>';
		if(empty($s_arr)) return;//没有可以更换的，直接返回
		eval(import_module('weapon'));
		//给自己的熟练度排序
		$skill_arr = Array('wp' => $pa['wp'], 'wk' => $pa['wk'], 'wg' => $pa['wg'], 'wc' => $pa['wc'], 'wd' => $pa['wd'], 'wf' => $pa['wf']);
		arsort($skill_arr);
		$skill_keys = array_keys($skill_arr);
		$fav0 = $fav1 = '';
		if($skill_arr[$skill_keys[0]] > $skill_arr[$skill_keys[5]]) $fav0 = $skill_keys[0];//如果熟练度最高的系别至少比最低的高而不是相等，记录第一高的熟练
		if($skill_arr[$skill_keys[1]] > $skill_arr[$skill_keys[2]]) $fav1 = $skill_keys[1];//如果熟练第二高的大于第三高的，记录第二高的
		//给要更换的武器加权
		$r_arr = array();
		$r_sum = 0;
		
		foreach($s_arr as $si){
			$svar = 100;//初始值100
			$itm=$pa['itm'.$si];
			$itmk=$pa['itmk'.$si];
			$itme=$pa['itme'.$si];
			$itms=$pa['itms'.$si];
			$itmsk=$pa['itmsk'.$si];
			
			$skind = $skillinfo[substr($itmk,1,1)];
			//var_dump($skind);
			//最得意的系别提高100加权
			if($fav0 == $skind) $svar += 100;
			//次得意的系别提高50加权
			elseif($fav1 == $skind) $svar += 50;
			
			//连击、双穿武器提高50加权
			foreach(Array('r','n','y') as $val){
				if(\itemmain\check_in_itmsk($val, $itmsk)) $svar += 50;
			}
			//如果武器有以下属性则每一种提高15加权
			foreach(Array('N','d','f','k','t','B','b') as $val){
				if(\itemmain\check_in_itmsk($val, $itmsk)) $svar += 15;
			}
			//如果玩家没有这种武器的防御属性，则提高200加权
			$ex_def_array = \attrbase\get_ex_def_array($pa, $pd, $active);
			eval(import_module('ex_phy_def'));
			$this_def_kind = $def_kind[substr($itmk,1,1)];
			if(!in_array('A', $ex_def_array) && !in_array($this_def_kind, $ex_def_array)) $svar += 200;
			//符提高50加权
			if($this_def_kind == 'F') $svar += 50;
			//如果武器不是无限耐且即将耗尽，则降低200加权，如果耐久极少则降低1000加权
			eval(import_module('itemmain'));
			if($itms != $nosta && $itms < 10) $svar -= 1000;
			elseif($itms != $nosta && $itms < 50) $svar -= 200;
			
			//如果是空枪，则降低1000加权
			if($itms == $nosta && (strpos($itmk,'WG')===0 || strpos($itmk,'WJ')===0)) $svar -= 1000;
			if($svar < 0) $svar = 0;
			
			$r_arr[$si] = $svar;
			$r_sum += $svar;
		}
		//DEBUG用
//		$r_arr_show = array();
//		foreach($r_arr as $i => $v){
//			$r_arr_show[$pa['itm'.$i]] = $v;
//		}
//		echo 'rarr ';var_dump($r_arr_show);echo '<br>';
		
		if($flag || rand(0,99) < calc_skill81rate($pa, $pd, $active)){//如果并非必须反击，则50%概率换武器
			$dice = rand(0, $r_sum);
			foreach($r_arr as $ri => $rv){
				if($dice < $rv) {
					break;
				}else{
					$dice -= $rv;
				}
			}
			swapitem81($pa, 'wep', $ri, $logflag);
		}
		return;
	}
	
	function calc_skill81rate(&$pa, &$pd, $active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill81'));
		return $skill81rate;
	}
	
	//把玩家装备位与道具位交换
	function swapitem81(&$pa, $ieqp, $in, $logflag=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if(!$pa || !in_array($ieqp, array('wep','arh','arb','ara','arf','art')) || !in_array($in, range(1,6))) return;
		
		$eqp = &$pa[$ieqp];
		$eqpk = &$pa[$ieqp.'k'];
		$eqpe = &$pa[$ieqp.'e'];
		$eqps = &$pa[$ieqp.'s'];
		$eqpsk = &$pa[$ieqp.'sk'];
		
		$itm=&$pa['itm'.$in];
		$itmk=&$pa['itmk'.$in];
		$itme=&$pa['itme'.$in];
		$itms=&$pa['itms'.$in];
		$itmsk=&$pa['itmsk'.$in];
		
		if (strpos ( $itmk, 'W' ) === 0)
		{
			$noeqp = 'WN';
		}else{
			$noeqp = 'DN';
		}
		swap($eqp,$itm);
		swap($eqpk,$itmk);
		swap($eqpe,$itme);
		swap($eqps,$itms);
		swap($eqpsk,$itmsk);
		$pa['wep_kind'] = \weapon\get_attack_method($pa);
		if($logflag) {
			eval(import_module('logger'));
			if (strpos ( $eqpk , $noeqp ) === 0 || !$eqps ) {
				$log .= "{$pa['name']}卸下了<span class=\"yellow b\">$itm</span>。<br>";
			} elseif(strpos ( $itmk , $noeqp ) === 0 || !$itms) {
				$log .= "{$pa['name']}迅速装备了<span class=\"yellow b\">$eqp</span>。<br>";
			}else{
				$log .= "{$pa['name']}迅速将<span class=\"red b\">$itm</span>卸下，装备了<span class=\"yellow b\">$eqp</span>！<br>";
			}
		}
		
		return;
	}
	
	//1. 被NPC袭击，NPC拥有1次换武器的权利
	function assault_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if (!$active) {
			if(\skillbase\skill_query(81,$pa) && check_unlocked81($pa)) {
				check_battleswap($pa, $pd, 1-$active);
			}
		}
	}
	
	//2. NPC反击判定前拥有1次换武器的权利
	function counter_assault_wrapper(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['hp'] > 0 && empty($pa['npc_evolved'])) //进化的NPC不换装
		{
			if(\skillbase\skill_query(81,$pa) && check_unlocked81($pa)) {
				check_battleswap($pa, $pd, $active, 1);
			}
		}
		$chprocess($pa, $pd, $active);
	}		
}

?>