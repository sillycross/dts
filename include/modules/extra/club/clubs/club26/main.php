<?php

namespace club26
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[26] = '妙手天成';
		$clublist[26] = Array(
			'type' => 1,
			'probability' => 100,
			'skills' => Array(
				10,11,12,
				97,
				98,99,100,101,102,103,
			)
		);
	}
}

?>
