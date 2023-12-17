<?php

namespace ex_cursed
{
	
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['O'] = '诅咒';
		$itemspkdesc['O']='无法丢弃，装备以后无法更换、卸下';
		$itemspkremark['O']='……';
	}
	
	function itemdrop_valid_check($itm, $itmk, $itme, $itms, $itmsk, $itmpos)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\itemmain\check_in_itmsk('O', $itmsk)){
			eval(import_module('logger'));
			if(check_enkan()) {
				$log .= '<span class="lime b">圆环之理的光辉暂时消解了道具的诅咒。</span><br>';
			}else{
				$log .= '<span class="red b">摆脱这个道具的诅咒是不可能的。</span><br>';
				return false;
			}
		}
		return $chprocess($itm, $itmk, $itme, $itms, $itmsk, $itmpos);
	}
	
	function itemoff_valid_check($itm, $itmk, $itme, $itms, $itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\itemmain\check_in_itmsk('O', $itmsk)){
			eval(import_module('logger'));
			if(check_enkan()) {
				$log .= '<span class="lime b">圆环之理的光辉暂时消解了道具的诅咒。</span><br>';
			}else{
				$log .= '<span class="red b">摆脱这个道具的诅咒是不可能的。</span><br>';
				return false;
			}
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
			if(\itemmain\check_in_itmsk('O', ${$obj.'sk'})){
				if(check_enkan()) {
					$log .= '<span class="lime b">圆环之理的光辉暂时消解了道具的诅咒。</span><br>';
				}else{
					$log .= '<span class="red b">摆脱这个道具的诅咒是不可能的。</span><br>';
					return;
				}
			}
		}
		$chprocess($theitem);
	}
	
	//不能把诅咒道具送给队友
	function senditem_check($edata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		$ret = $chprocess($edata);
		if($ret){
			$itmn = substr($command, 4);
			if (!empty(${'itms'.$itmn})) {
				if(\itemmain\check_in_itmsk('O', ${'itmsk'.$itmn})){
					if(check_enkan()) {
						$log .= '<span class="lime b">圆环之理的光辉暂时消解了道具的诅咒。</span><br>';
					}else{
						$log .= '<span class="red b">摆脱这个道具的诅咒是不可能的。</span><br>';
						$ret = false;
					}
				}
			}			
		}
		return $ret;
	}
	
	//恶趣味，装备或者包裹里有破则的时候，诅咒暂时失效
	function check_enkan(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','itemmain'));
		$flag = 0;
		foreach($equip_list as $v){
			if(strpos(${$v}, '概念武装『破则』')!==false){
				$flag = 1;
				break;
			}
		}
		return $flag;
	}
	
	//诅咒属性不能加宝石
	function geming_objvalid($t1, $itm, $itmk, $itme, $itms ,$itmsk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($t1, $itm, $itmk, $itme, $itms ,$itmsk);
		if($ret && !check_enkan() && \itemmain\check_in_itmsk('O', $itmsk)) {
			eval(import_module('logger'));
			$log.='<span class="red b">目标道具附带的诅咒把宝石弹开了。</span><br>';
			$ret = false;
		}
		return $ret;
	}
}


?>