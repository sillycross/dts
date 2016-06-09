<?php

namespace club1
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[1] = '街头霸王';
		$clublist[1] = Array(
			'type' => 0,
			'probability' => 100, 
			'skills' => Array(
				10,11,12,	
				13,		//初始25殴熟，升级时加殴熟
				32,35,36,37,38,34,
			)
		);
	}
}

?>
