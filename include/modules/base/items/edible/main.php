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
	
	function edible_recover($itm, $hpup, $spup)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		$hp+=$hpup; $sp+=$spup;
		$log .= "你使用了<span class=\"red\">$itm</span>，恢复了";
		if ($hpup>0) $log.="<span class=\"yellow\">$hpup</span>点生命";
		if ($hpup>0 && $spup>0) $log.='和';
		if ($spup>0) $log.="<span class=\"yellow\">$spup</span>点体力";
		$log.="。<br>";
	}
	
	function itemuse_edible(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'HS' ) === 0) {
			if ($sp < $msp) {
				$spup = get_edible_spup($theitem);
				$spup = min($msp-$sp,$spup);
				$spup = max(0,$spup);
				edible_recover($itm,0,$spup);
				\itemmain\itms_reduce($theitem);
			} else {
				$log .= '你的体力不需要恢复。<br>';
			}
		} elseif (strpos ( $itmk, 'HH' ) === 0) {
			if ($hp < $mhp) {
				$hpup = get_edible_hpup($theitem);
				$hpup = min($mhp-$hp,$hpup);
				$hpup = max(0,$hpup);
				edible_recover($itm,$hpup,0);
				\itemmain\itms_reduce($theitem);
			} else {
				$log .= '你的生命不需要恢复。<br>';
			}
		} elseif (strpos ( $itmk, 'HB' ) === 0) {
			if (($hp < $mhp) || ($sp < $msp)) {
				$spup = get_edible_spup($theitem);
				$hpup = get_edible_hpup($theitem);
				$spup = min($msp-$sp,$spup);
				$hpup = min($mhp-$hp,$hpup);
				$spup = max(0,$spup);
				$hpup = max(0,$hpup);
				edible_recover($itm,$hpup,$spup);
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
