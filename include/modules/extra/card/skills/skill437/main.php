<?php

namespace skill437
{
	function init() 
	{
		define('MOD_SKILL437_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[437] = 'EM';
	}
	
	function acquire437(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost437(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked437(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		if($mode == 'combat' && $command == 'back' && strpos($action, 'enemy')===0) 
		{
			eval(import_module('weapon'));
			if ((\skillbase\skill_query(437))&&(check_unlocked437())){
				${$skillinfo[substr($wepk,1,1)]}+=3;
				\lvlctl\getexp(2);
				//$log .= '<span class="grey b">“今天摸了！”</span>你一边关闭直播一边给自己补了3点熟练和2点经验。<br>';
			}
		}
		
		$chprocess();
	}
}

?>
