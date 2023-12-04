<?php

namespace item_um
{
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['M'] = '强化药物';
		$iteminfo['MA'] = '攻击药物';
		$iteminfo['MD'] = '防御药物';
		$iteminfo['ME'] = '经验药物';
		$iteminfo['MS'] = '体力强化';
		$iteminfo['MH'] = '生命强化';
		$iteminfo['MV'] = '熟练强化';
	}
	
	function calc_skillmed_value($x, $v)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$k=pow($v,0.5)/25+1.05;
		return pow($x/($k-1)/$v+1,1-$k)*$v;
	}
	
	function calc_skillmed_efct($itme, $skcnt, $ws_sum)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$skill_minimum = 60;
		$skill_limit = 600;
		if ($ws_sum < $skill_minimum * $skcnt) {
			$mefct = $itme;
		} elseif ($ws_sum < $skill_limit * $skcnt) {
			$mefct = round(calc_skillmed_value($ws_sum-$skill_minimum*$skcnt,$itme));
		} else {
			$mefct = 0;
		}
		return $mefct;
	}

	function itemuse_um(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		eval(import_module('player','weapon','logger','lvlctl'));
		
		$log .= "你服用了<span class=\"red b\">$itm</span>。<br>";
			
		if (strpos ( $itmk, 'MA' ) === 0) {
			$att_min = 200;
			$att_limit = 500;
			$dice = rand ( - 5, 5 );
			if ($att < $att_min) {
				$mefct = $itme;
			} elseif ($att < $att_limit) {
				$mefct = round ( $itme * (1 - ($att - $att_min) / ($att_limit - $att_min)) );
			} else {
				$mefct = 0;
			}
			/*
			if ($mefct < 5) {
				if ($mefct < $dice) {
					$mefct = - $dice;
				}
			}
			*/
			$att += $mefct;
			$mdname = "基础攻击力";
		} elseif (strpos ( $itmk, 'MD' ) === 0) {
			$def_min = 200;
			$def_limit = 500;
			$dice = rand ( - 5, 5 );
			if ($def < $def_min) {
				$mefct = $itme;
			} elseif ($def < $def_limit) {
				$mefct = round ( $itme * (1 - ($def - $def_min) / ($def_limit - $def_min)) );
			} else {
				$mefct = 0;
			}
			/*
			if ($mefct < 5) {
				if ($mefct < $dice) {
					$mefct = - $dice;
				}
			}
			*/
			$def += $mefct;
			$mdname = "基础防御力";
		} elseif (strpos ( $itmk, 'ME' ) === 0) {
			$lvlup_objective = $itme / 10;
			$mefct = \lvlctl\calc_upexp($lvlup_objective) - rand( 4, 8);
			//$mefct = round ( $baseexp * 2 * $lvlup_objective + rand ( 0, 5 ) );
			$mdname = "经验值";	//经验值增加最后加，因为log的次序关系
		} elseif (strpos ( $itmk, 'MS' ) === 0) {
			$mefct = $itme;
			$sp += $mefct;
			$msp += $mefct;
			$mdname = "体力上限";
		} elseif (strpos ( $itmk, 'MH' ) === 0) {
			$mefct = $itme;
			$hp += $mefct;
			$mhp += $mefct;
			$mdname = "生命上限";
		} elseif (strpos ( $itmk, 'MV' ) === 0) {
			$skcnt = 0; $ws_sum = 0;
			foreach (array_unique(array_values($skillinfo)) as $key)
			{
				$skcnt++;
				$ws_sum += $$key;
			}
			// $dice = rand ( - 10, 10 );
			$mefct = calc_skillmed_efct($itme, $skcnt, $ws_sum);
			/*
			if ($mefct < 10) {
				if ($mefct < $dice) {
					$mefct = - $dice;
				}
			}
			*/
			foreach (array_unique(array_values($skillinfo)) as $key)
				$$key += $mefct;
				
			$mdname = "全系熟练度";
		}
		if (strpos ( $itmk, 'ME' ) === 0) $mefct = \lvlctl\getexp($mefct) ? $mefct : 0;
		if ($mefct > 0) {
			$log .= "身体里有种力量涌出来！<br>你的{$mdname}提高了<span class=\"yellow b\">$mefct</span>点！<br>";
		} elseif ($mefct == 0) {
			$log .= "已经很强了，却还想靠药物继续强化自己，是不是太贪心了？<br>你的能力没有任何提升。<br>";
		} else {
			$mefct = - $mefct;
			$log .= "已经很强了，却还想靠药物继续强化自己，是不是太贪心了？<br>你贪婪的行为引发了药物的副作用！<br>你的{$mdname}下降了<span class=\"red b\">$mefct</span>点！<br>";
		}
		
		\itemmain\itms_reduce($theitem);
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'M' ) === 0) {
			itemuse_um($theitem);
			return;
		}
		$chprocess($theitem);
	}
}

?>
