<?php

namespace club7
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[7] = '锡安成员';
		$clublist[7] = Array(
			'type' => 1,
			'probability' => 100, 
			'skills' => Array(
				10,11,12,
				230,231,232,233,235,234,236,237,238
			)
		);
	}
}

?>
