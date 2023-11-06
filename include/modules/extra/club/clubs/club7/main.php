<?php

namespace club7
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[7] = '边缘行者';
		$clublist[7] = Array(
			'type' => 1,
			'probability' => 100, 
			'skills' => Array(
				10,11,12,
				230,233,235,
				231,236,232,237,238,234,
			)
		);
	}
}

?>
