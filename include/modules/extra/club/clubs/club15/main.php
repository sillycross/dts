<?php

namespace club15
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[15] = '<span class="L5 b">L5状态</span>';
		$clublist[15] = Array(
			'type' => 1,
			'probability' => 0,
			'skills' => Array(
			)
		);
	}
}

?>
