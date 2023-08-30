<?php

namespace ex_def_down
{
	
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['^dd'] = '降防';
		$itemspkdesc['^dd']='你的总防御力减少<:skn:>%，效果可叠加';
		$itemspkremark['^dd']='具体降低数值视装备而定';
	}
	
	function get_def_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		$flag = \attrbase\check_in_itmsk('^dd', \attrbase\get_ex_def_array($pa, $pd, $active), 1);
		if (false !== $flag) {
			if($flag > 99) $flag = 99;//最多降99%
			$var = 1 - $flag / 100;
			array_unshift($ret, $var);
		}
		return $ret;
	}
}

?>