<?php

namespace club19
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[19] = '铁拳无敌';
		$clublist[19] = Array(
			'type' => 1,
			'probability' => 4011111, 
			'skills' => Array(
				10,11,12,		
				256,258,257,260,259,261,	
			)
		);
	}
}

?>
