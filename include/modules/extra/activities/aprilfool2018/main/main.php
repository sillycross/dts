<?php

namespace aprilfool2018
{
	
	function init() 
	{
	}
	
	//如果是大房间且在活动期间，开场新增NPC
	function get_npclist() {
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		$ret = $chprocess();
		if(in_array($gametype, array(0,4)) && \achievement_base\check_achtype_available(34)) {
			eval(import_module('aprilfool2018'));
			foreach($snpcinfo as $i => $v){
				$ret[$i] = $v;
			}
		}
		return $ret;
	}
}
?>