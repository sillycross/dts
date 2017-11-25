<?php

namespace club14
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[14] = '肌肉兄贵';
		$clublist[14] = Array(
			'type' => 1,
			'probability' => 100, 
			'skills' => Array(
				10,39,12,		//攻防成长增加
				79,//显示用的假技能
				44,40,45,43,41,42,46,
			)
		);
	}
}

?>
