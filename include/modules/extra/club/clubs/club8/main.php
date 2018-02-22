<?php

namespace club8
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[8] = '黑衣组织';
		$clublist[8] = Array(
			'type' => 1,
			'probability' => 100, 
			'skills' => Array(
				10,11,12,	
				219,220,
				217,218,221,222,223,224
			)
		);
	}
}

?>
