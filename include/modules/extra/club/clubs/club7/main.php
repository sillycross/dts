<?php

namespace club7
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[7] = '锡安成员';
		$clublist[7] = Array(
			'type' => 1,
			'probability' => 0, 
			'skills' => Array(
				10,11,12,	
			)
		);
	}
}

?>
