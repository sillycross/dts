<?php

namespace club4
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[4] = '灌篮高手';
		$clublist[4] = Array(
			'type' => 0,
			'probability' => 10000, 
			'skills' => Array(
				10,11,12,	
				16,		//投熟
				52,47,48,49,50,51,
			)
		);
	}
}

?>
