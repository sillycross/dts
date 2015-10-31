<?php

namespace club5
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[5] = '拆弹专家';
		$clublist[5] = Array(
			'type' => 0,
			'probability' => 100, 
			'skills' => Array(
				10,11,12,	
				17,		//爆熟
				19,		//陷阱相关 
				20,		//爆系合成耐久
				211,212,213,214,215,216,
			)
		);
	}
}

?>
