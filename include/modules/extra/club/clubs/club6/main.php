<?php

namespace club6
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[6] = '宛如疾风';
		$clublist[6] = Array(
			'type' => 1,
			'probability' => 0, 
			'skills' => Array(
				10,11,12,	
				53,
			)
		);
	}
}

?>
