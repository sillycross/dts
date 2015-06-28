<?php

namespace edible
{
	function init()
	{
		eval(import_module('itemmain'));
		$iteminfo['HH'] = '生命恢复';
		$iteminfo['HS'] = '体力恢复';
		$iteminfo['HB'] = '命体恢复';
	}
	
	function get_edible_spup(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $theitem['itme'];
	}
	
	function get_edible_hpup(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $theitem['itme'];
	}
	
	function itemuse_edible(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'HS' ) === 0) {
			if ($sp < $msp) {
				$oldsp = $sp;
				//if $club == 16
				$spup = get_edible_spup($theitem);
				$sp += $spup;
				$sp = $sp > $msp ? $msp : $sp;
				$oldsp = $sp - $oldsp;
				$log .= "你使用了<span class=\"red\">$itm</span>，恢复了<span class=\"yellow\">$oldsp</span>点体力。<br>";
				\itemmain\itms_reduce($theitem);
			} else {
				$log .= '你的体力不需要恢复。<br>';
			}
		} elseif (strpos ( $itmk, 'HH' ) === 0) {
			if ($hp < $mhp) {
				$oldhp = $hp;
				$hpup = get_edible_hpup($theitem);
				$hp += $hpup;
				$hp = $hp > $mhp ? $mhp : $hp;
				$oldhp = $hp - $oldhp;
				$log .= "你使用了<span class=\"red\">$itm</span>，恢复了<span class=\"yellow\">$oldhp</span>点生命。<br>";
				\itemmain\itms_reduce($theitem);
			} else {
				$log .= '你的生命不需要恢复。<br>';
			}
		} elseif (strpos ( $itmk, 'HB' ) === 0) {
			if (($hp < $mhp) || ($sp < $msp)) {
				$spup = get_edible_spup($theitem);
				$hpup = get_edible_hpup($theitem);
				$oldsp = $sp;
				$sp += $spup;
				$sp = $sp > $msp ? $msp : $sp;
				$oldsp = $sp - $oldsp;
				$oldhp = $hp;
				$hp += $hpup;
				$hp = $hp > $mhp ? $mhp : $hp;
				$oldhp = $hp - $oldhp;
				$log .= "你使用了<span class=\"red\">$itm</span>，恢复了<span class=\"yellow\">$oldhp</span>点生命和<span class=\"yellow\">$oldsp</span>点体力。<br>";
				\itemmain\itms_reduce($theitem);
			} else {
				$log .= '你的生命和体力都不需要恢复。<br>';
			}
		}
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'HS' ) === 0 || strpos ( $itmk, 'HH' ) === 0 || strpos ( $itmk, 'HB' ) === 0)
		{
			itemuse_edible($theitem);
			return;
		}
		$chprocess($theitem);
	}	
}

?>
