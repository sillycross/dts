<?php

namespace club97
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[97] = '装逼战士';
		$clublist[97] = Array(
			'type' => 1,
			'probability' => 0, 
			'skills' => Array(
				10,
				11,
				12,
			)
		);
	}
}

?>