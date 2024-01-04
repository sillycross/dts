<?php

namespace club10
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[10] = '高速成长';
		$clublist[10] = Array(
			'type' => 1,
			'probability' => 100, 
			'skills' => Array(
				10,11,12,78,
				225,
				85,226,270,229,271,228,
			)
		);
	}
}

?>
