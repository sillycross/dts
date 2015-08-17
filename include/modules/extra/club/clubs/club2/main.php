<?php

namespace club2
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[2] = '见敌必斩';
		$clublist[2] = Array(
			'type' => 0,
			'probability' => 10000, 
			'skills' => Array(
				10,11,12,	
				14,		//斩熟
				207,75,208,209,210
			)
		);
	}
}

?>
