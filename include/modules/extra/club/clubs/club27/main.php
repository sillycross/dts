<?php

namespace club27
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[27] = '深渊学者';
		$clublist[27] = Array(
			'type' => 1,
			'probability' => 100,
			'skills' => Array(
				10,11,12,
				107,
				108,109,110,111,112,113,
			)
		);
	}
}

?>
