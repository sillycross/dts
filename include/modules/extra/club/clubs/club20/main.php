<?php

namespace club20
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[20] = '宝石骑士';
		$clublist[20] = Array(
			'type' => 1,
			'probability' => 100, 
			'skills' => Array(
				10,11,12,		
				23,24,25,26,27,		
			)
		);
	}
}

?>
