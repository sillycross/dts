<?php

namespace npcchat_bubble
{
	$npcchat_bubble_on = 0;
	$npcchat_bubble_replace_log = 0;
	
	function init() {}
	
	function npcchat_print($printlog)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','npcchat_bubble'));
		if($npcchat_bubble_on) {
			if(!isset($uip['npcchat']['enemy'])) $uip['npcchat']['enemy']=array();
			$uip['npcchat']['enemy'][] = $printlog;
		}		
		if(!$npcchat_bubble_on || !$npcchat_bubble_replace_log) {
			$chprocess($printlog);
		}
	}
	
	function npcchat_bubble_show($tp='enemy')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(!isset($uip['npcchat'][$tp])) return '';
		$bubblecont = $uip['npcchat'][$tp];
		ob_start();
		include template(MOD_NPCCHAT_BUBBLE_BUBBLEPAGE);
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
}

?>