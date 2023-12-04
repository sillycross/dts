<?php

namespace ex_uselog
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['^hlog'] = '讯息（在前）';//不显示
		$itemspkinfo['^tlog'] = '讯息（在后）';//不显示
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$itmsk = &$theitem['itmsk'];
		$headlog = \itemmain\check_in_itmsk('^hlog', $itmsk);
		$taillog = \itemmain\check_in_itmsk('^tlog', $itmsk);
		if (!empty($headlog))
		{
			eval(import_module('logger'));
			$log .= \attrbase\base64_decode_comp_itmsk($headlog);
		}
		$chprocess($theitem);
		if (!empty($taillog))
		{
			eval(import_module('logger'));
			$log .= \attrbase\base64_decode_comp_itmsk($taillog);
		}
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		$ret = $chprocess($cinfo);
		if ($ret) {
			if (strpos($cinfo[0], '^hlog') === 0) return false;
			if (strpos($cinfo[0], '^tlog') === 0) return false;
		}
		return $ret;
	}
	
}

?>
