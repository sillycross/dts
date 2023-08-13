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
		$log .= "你使用了<span class=\"lime b\">$itm</span>";
		$recoverlog = '';
		if ($hpup>0) {
			$recoverlog .= "<span class=\"yellow b\">$hpup</span>点生命";
			if($spup>0) $recoverlog.='和';
		}
		if ($spup>0) {
			$recoverlog .= "<span class=\"yellow b\">$spup</span>点体力";
		}
		if (!empty($recoverlog)) {
			$log .= '，恢复了'.$recoverlog.'。<br>';
		}else{
			$log .= '。这东西味同嚼蜡，吃了跟没吃一样。<br>';
		}
	}
	
	//获得最大SP值
	function itemuse_edible_get_msp(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		return $msp;
	}
	
	//获得最大HP值
	function itemuse_edible_get_mhp(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		return $mhp;
	}
	
	function itemuse_edible(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		$tmp_msp = itemuse_edible_get_msp();
		$tmp_mhp = itemuse_edible_get_mhp();
		
		if (strpos ( $itmk, 'HS' ) === 0) {
			if ($sp < $tmp_msp) {
				$spup = get_edible_spup($theitem);
				$spup = min($tmp_msp-$sp,$spup);
				$spup = max(0,$spup);
				edible_recover($itm,0,$spup);
				\itemmain\itms_reduce($theitem);
			} else {
				$log .= '你的体力不需要恢复。<br>';
			}
		} elseif (strpos ( $itmk, 'HH' ) === 0) {
			if ($hp < $tmp_mhp) {
				$hpup = get_edible_hpup($theitem);
				$hpup = min($tmp_mhp-$hp,$hpup);
				$hpup = max(0,$hpup);
				edible_recover($itm,$hpup,0);
				\itemmain\itms_reduce($theitem);
			} else {
				$log .= '你的生命不需要恢复。<br>';
			}
		} elseif (strpos ( $itmk, 'HB' ) === 0) {
			if (($hp < $tmp_mhp) || ($sp < $tmp_msp)) {
				$spup = get_edible_spup($theitem);
				$hpup = get_edible_hpup($theitem);
				$spup = min($tmp_msp-$sp,$spup);
				$hpup = min($tmp_mhp-$hp,$hpup);
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
