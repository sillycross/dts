<?php

namespace club21
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[21] = '最强大脑';
		$clubdesc_h[21] = $clubdesc_a[21] = '称号特性只是装饰，技能才是本体';
		$clublist[21] = Array(
			'type' => 1,
			'probability' => 100, 
			'skills' => Array(
				10,11,12,		
				239,240,241,242,243,244,
			)
		);
	}
}

?>