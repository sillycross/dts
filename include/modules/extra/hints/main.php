<?php

namespace hints
{
	function init() {}
	
	function get_tutorial_hints(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill1000','hints'));
		$step = \skillbase\skill_getvalue(1000,'step',$pa);
		$r = Array(
			$hintsetting[$step]['tips'],
			$hintsetting[$step]['allowed']
		);
		return $r;
	}
}

?>