<?php

namespace club9
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[9] = '超能力者';
		$clublist[9] = Array(
			'type' => 0,
			'probability' => 70, 
			'skills' => Array(
				10,11,12,	
				18,		//灵熟
				65,73,76,74,
			)
		);
	}
}

?>
