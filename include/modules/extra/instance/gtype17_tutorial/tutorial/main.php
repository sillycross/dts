<?php

namespace tutorial
{
	function init() {}
	
	function get_tutorial(){//教程专用的提示，所以跟skill1000挂钩。
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill1000','tutorial'));
		$step = \skillbase\skill_getvalue(1000,'step',$pa);
		$tsetting = $tutorialsetting[$step];
		if(!empty($tsetting['pulse'])) {
			$chasschg[$tsetting['pulse']] = $tsetting['pulseclass'];
		}
		$r = Array(
			$tsetting['tips'],
			$tsetting['allowed']
		);
		return $r;
	}
	
//	function act()	
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		
//		eval(import_module('sys','player','input'));
//		$chprocess();
//		if ($gametype == 17) {
//			$mode = 'tutorial';
//		}
//		return;
//	}
	
//	function post_act()
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','player','input'));
//		if ($gametype == 17) {
//			$mode = 'tutorial';
//		}
//		return;
//	}
}

?>