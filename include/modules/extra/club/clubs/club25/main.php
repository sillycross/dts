<?php

namespace club25
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[25] = '偶像歌姬';
		$clublist[25] = Array(
			'type' => 1,
			'probability' => 100,
			'skills' => Array(
				10,11,12,
				87,
				88,89,90,91,92,93,94,
			)
		);
	}
}

?>
