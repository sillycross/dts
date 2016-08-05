<?php

namespace club18
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[18] = '天赋异秉';
		$clublist[18] = Array(
			'type' => 1,
			'probability' => 100, 
			'skills' => Array(
				10,11,12,
				70,
				72,71,77,
			)
		);
	}
}

?>
