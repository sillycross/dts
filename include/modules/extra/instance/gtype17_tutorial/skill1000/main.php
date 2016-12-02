<?php

namespace skill1000
{
	function init() 
	{
		define('MOD_SKILL1000_INFO','unique;');
	}
	
	function acquire1000(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(1000,'step','0',$pa);
	}
	
	function lost1000(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked1000(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
//	function discover_item(){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','player','logger','itemmain','skillbase'));
//		if($gametype != 17) return $chprocess();		
//		if(0) {
//			//假造一个item
//		}else {
//			$log .= "请按教程的要求执行。<br>";
//			$mode = 'command';
//			return;
//		}
//	}
	
//	function search(){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','player','map','logger','explore'));
//		if($gametype != 17) return $chprocess();
//		if(\skillbase\skill_getvalue(1000,'step',$pa)==1) {
//			//假造一个item
//		}else {
//			$log .= "<span class='yellow'>请按教程的要求执行。</span><br>";
//			$mode = 'command';
//			return;
//		}
//	}
}

?>
