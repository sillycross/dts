<?php

namespace club3
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[3] = '狙击鹰眼';
		$clublist[3] = Array(
			'type' => 0,
			'probability' => 100, 
			'skills' => Array(
				10,11,12,	
				15,		//射熟
				201,202,203,204,205,265,206,
			)
		);
	}
}

?>
