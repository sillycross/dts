<?php

namespace ammunition
{
	function init()
	{
		eval(import_module('itemmain'));
		$iteminfo['GB'] = '手枪弹药';	
		$iteminfo['GBr'] = '机枪弹药';
		$iteminfo['GBi'] = '气体弹药';
		$iteminfo['GBh'] = '重型弹药';
		$iteminfo['GBe'] = '能源弹药';
		
		$itemspkinfo['o'] = '一发';
		
	}
	
	function itemuse_ugb(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if ((strpos ( $wepk, 'WG' ) !== 0)&&(strpos ( $wepk, 'WJ' ) !== 0)) {
			$log .= "<span class=\"red\">你没有装备枪械，不能使用子弹。</span><br>";
			$mode = 'command';
			return;
		}
		if (strpos ($wepk,'WG')===false){
			if ($itmk=='GBh'){
				$bulletnum = 1;	
			}else{
				$log .= "<span class=\"red\">枪械类型和弹药类型不匹配。</span><br>";
				$mode = 'command';
				return;
			}
		}
		elseif (strpos ( $wepsk, 'o' ) !== false) {
			$log .= "<span class=\"red\">{$wep}不能装填弹药。</span><br>";
			$mode = 'command';
			return;
		} elseif (strpos ( $wepsk, 'e' ) !== false || strpos ( $wepsk, 'w' ) !== false) {
			if ($itmk == 'GBe') {
				$bulletnum = 10;
			} else {
				$log .= "<span class=\"red\">枪械类型和弹药类型不匹配。</span><br>";
				$mode = 'command';
				return;
			}
		} elseif (strpos ( $wepsk, 'i' ) !== false || strpos ( $wepsk, 'u' ) !== false) {
			if ($itmk == 'GBi') {
				$bulletnum = 10;
			} else {
				$log .= "<span class=\"red\">枪械类型和弹药类型不匹配。</span><br>";
				$mode = 'command';
				return;
			}
		} else {
			if (strpos ( $wepsk, 'r' ) !== false) {
				if ($itmk == 'GBr') {
					$bulletnum = 20;
				} else {
					$log .= "<span class=\"red\">枪械类型和弹药类型不匹配。</span><br>";
					$mode = 'command';
					return;
				}
			} else {
				if ($itmk == 'GB') {
					$bulletnum = 6;
				} else {
					$log .= "<span class=\"red\">枪械类型和弹药类型不匹配。</span><br>";
					$mode = 'command';
					return;
				}
			}
		}
		if ($weps == $nosta) {
			$weps = 0;
		}
		$bullet = $bulletnum - $weps;
		if ($bullet <= 0) {
				$log .= "<span class=\"red\">{$wep}的弹匣是满的，不能装弹。</span>";
			return;
		} elseif ($bullet >= $itms) {
			$bullet = $itms;
		}
		$itms -= $bullet;
		$weps += $bullet;
		$log .= "为<span class=\"red\">$wep</span>装填了<span class=\"red\">$itm</span>，<span class=\"red\">$wep</span>残弹数增加<span class=\"yellow\">$bullet</span>。<br>";
		if ($itms <= 0) {
			$log .= "<span class=\"red\">$itm</span>用光了。<br>";
			$itm = $itmk = $itmsk = '';
			$itme = $itms = 0;
		}
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'GB' ) === 0) 
		{
			itemuse_ugb($theitem);
			return;
		}
		$chprocess($theitem);
	}
	
}

?>
