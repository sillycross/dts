<?php

namespace skill1000
{
	function init() 
	{
		define('MOD_SKILL1000_INFO','hidden;');
		eval(import_module('cardbase'));
	}
	
	function acquire1000(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(1000,'tno','1',$pa);
		\skillbase\skill_setvalue(1000,'step','10',$pa);
		\skillbase\skill_setvalue(1000,'prog','0',$pa);
		\skillbase\skill_setvalue(1000,'showtips','1',$pa);
		\skillbase\skill_setvalue(1000,'countdown','0',$pa);
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
	
//	function set_process1000($s,$p=0,&$pa){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if(check_process1000($pa)){
//			\skillbase\skill_setvalue(1000,'step',$s,$pa);
//			\skillbase\skill_setvalue(1000,'prog',$p,$pa);
//		}
//		return;
//	}
	
	function check_process1000(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(1000)) {
			$log.='不存在教程技能，这可能是一个bug，请检查skill1000模块。';
			return false;
		}
		return true;
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
//			$log .= "<span class='yellow b'>请按教程的要求执行。</span><br>";
//			$mode = 'command';
//			return;
//		}
//	}
}

?>
