<?php

namespace item_ub
{
	global $elec_cap; $elec_cap = 5;
	
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['B'] = '电池';
	}
	
	function itemuse_ub(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		eval(import_module('sys','player','logger','item_ub'));
		
		$flag = false;
		$bat_kind = substr($itmk,1,1);
		for($i = 1; $i <= 6; $i ++) {
			if (${'itmk' . $i} == 'E'.$bat_kind && ${'itms' . $i}) {
				if(${'itme' . $i} >= $elec_cap){
					$log .= "包裹{$i}里的<span class=\"yellow\">${'itm'.$i}</span>已经充满电了。<br>";
				}else{
					${'itme' . $i} += $itme;
					if(${'itme' . $i} > $elec_cap){${'itme' . $i} = $elec_cap;}
					$itms --;
					$flag = true;
					$log .= "为包裹{$i}里的<span class=\"yellow\">${'itm'.$i}</span>充了电。";
					break;
				}				
			}
		}
		if (! $flag) {
			$log .= '你没有需要充电的物品。<br>';
		}
		if ($itms <= 0 && $itm) {
			$log .= "<span class=\"red\">$itm</span>用光了。<br>";
			$itm = $itmk = $itmsk = '';
			$itme = $itms = 0;
		}		
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'B' ) === 0) {
			itemuse_ub($theitem);
			return;
		}
		$chprocess($theitem);
	}
}

?>
