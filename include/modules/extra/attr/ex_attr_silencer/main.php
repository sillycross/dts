<?php

namespace ex_attr_silencer
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['S'] = '消音';
		$itemspkdesc['S']='攻击时不会发出枪声';
		$itemspkremark['S']='武器类型为远程武器/重型枪械方有效。不影响爆炸声及其他会发出声响的属性';
	}
	
	function weapon_WG_addnoise(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\attrbase\check_in_itmsk('S',\attrbase\get_ex_attack_array($pa, $pd, $active))) return; 
		$chprocess($pa, $pd, $active);
	}
	
	function weapon_WJ_addnoise(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\attrbase\check_in_itmsk('S',\attrbase\get_ex_attack_array($pa, $pd, $active))) return;
		$chprocess($pa, $pd, $active);
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0) 
			if ($itm == '消音器') 
			{
				if (strpos ( $wepk, 'WG' ) !== 0 && strpos ( $wepk, 'WJ' ) !== 0) {
					$log .= '你没有装备枪械，不能使用消音器。<br>';
				} elseif (\itemmain\check_in_itmsk('S', $wepsk)) {
					$log .= "你的武器已经安装了消音器。<br>";
				} else {
					$wepsk .= 'S';
					$log .= "你给<span class=\"yellow b\">$wep</span>安装了<span class=\"yellow b\">$itm</span>。<br>";
					\itemmain\itms_reduce($theitem);
				}
				return;
			}
		$chprocess($theitem);
	}
}

?>