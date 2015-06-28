<?php

namespace club99
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[99] = '第一形态';
		$clublist[99] = Array(
			'type' => 1,
			'probability' => 0,
			'skills' => Array(
				21,		//第一形态NPC技能
			)
		);
	}
}

?>
