<?php

namespace ex_def_down
{
	
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['L'] = '直击';
		$itemspkdesc['L']='攻击时可能无视敌方一切技能';
		$itemspkremark['L']='30%概率生效';
	}
	
	
}

?>