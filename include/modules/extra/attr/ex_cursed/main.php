<?php

namespace ex_cursed
{
	
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['O'] = '诅咒';
		$itemspkdesc['O']='装备以后无法更换、卸下或丢弃';
		$itemspkremark['O']='……';
	}
	
	function itemdrop_valid_check($itm, $itmk, $itme, $itms, $itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(in_array('O',\itemmain\get_itmsk_array($itmsk))){
			eval(import_module('logger'));
			$log .= '<span class="red">摆脱这个装备的诅咒是不可能的。</span><br>';
			return false;
		}
		return $chprocess($itm, $itmk, $itme, $itms, $itmsk);
	}
	
	function itemoff_valid_check($itm, $itmk, $itme, $itms, $itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(in_array('O',\itemmain\get_itmsk_array($itmsk))){
			eval(import_module('logger'));
			$log .= '<span class="red">摆脱这个装备的诅咒是不可能的。</span><br>';
			return false;
		}
		return $chprocess($itm, $itmk, $itme, $itms, $itmsk);
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if(strpos ( $itmk, 'W' ) === 0 || strpos ( $itmk, 'D' ) === 0 || strpos ( $itmk, 'A' ) === 0) {
			eval(import_module('player','logger'));
			if(strpos ( $itmk, 'W' ) === 0) $obj = 'wep';
			elseif(strpos ( $itmk, 'DB' ) === 0) $obj = 'arb';
			elseif(strpos ( $itmk, 'DH' ) === 0) $obj = 'arh';
			elseif(strpos ( $itmk, 'DA' ) === 0) $obj = 'ara';
			elseif(strpos ( $itmk, 'DF' ) === 0) $obj = 'arf';
			elseif(strpos ( $itmk, 'A' ) === 0) $obj = 'art';
			if(in_array('O',\itemmain\get_itmsk_array(${$obj.'sk'}))){
				$log .= '<span class="red">摆脱这个装备的诅咒是不可能的。</span><br>';
				return;
			}
		}
		$chprocess($theitem);
	}
}

?>