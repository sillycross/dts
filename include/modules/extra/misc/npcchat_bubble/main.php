<?php

namespace npcchat_bubble
{
	function init() {}
	
	function npcchat_print($printlog)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(!isset($uip['npcchat']['enemy'])) $uip['npcchat']['enemy']=array();
		$uip['npcchat']['enemy'][] = $printlog;
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