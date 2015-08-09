<?php

namespace club11
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[11] = '富家子弟';
		$clublist[11] = Array(
			'type' => 1,
			'probability' => 10000, 
			'skills' => Array(
				10,11,12,	
				55,56,
			)
		);
	}
}

?>
