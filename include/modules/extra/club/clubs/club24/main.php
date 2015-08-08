<?php

namespace club24
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[24] = '亡灵骑士';
		$clublist[24] = Array(
			'type' => 1,
			'probability' => 100, 
			'skills' => Array(
				10,11,12,		
				24,		//骑士系人物HP成长
				58,		//复活	
				61,62,60,64,63,59,		
			)
		);
	}
}

?>
