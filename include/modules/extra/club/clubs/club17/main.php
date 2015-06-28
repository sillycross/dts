<?php

namespace club17
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[17] = '走路萌物';
		$clublist[17] = Array(
			'type' => 1,
			'probability' => 0, 
			'skills' => Array(
				12,		//只有治疗	
				22,		//踩雷率增加
			)
		);
	}
}

?>
