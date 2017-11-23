<?php

namespace club13
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[13] = '根性兄贵';
		$clublist[13] = Array(
			'type' => 1,
			'probability' => 100, 
			'skills' => Array(
				29,11,12,		//生命成长增加
				31,28,30,
				267,268,269
			)
		);
	}
}

?>
