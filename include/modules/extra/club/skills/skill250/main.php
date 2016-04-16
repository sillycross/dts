<?php

namespace skill250
{
	function init() 
	{
		define('MOD_SKILL250_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[250] = '预感';
	}
	
	function acquire250(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost250(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked250(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function trap_deal_damage()	//嘲讽
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(250)) 
		{
			eval(import_module('logger'));
			$log.='<span class="clan">“为什么莫名地感到有些不安……” </span><br>';
		}
		return $chprocess();
	}
	
	function get_trap_damage_multiplier(&$pa, &$pd, $trap, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(250,$pd)) 
		{
			eval(import_module('logger'));
			$log.='幸好已有预感，你及时地护住了脸……<br>';
			$r=Array(1-rand(1,30)/100);
		}
		return array_merge($r,$chprocess($pa,$pd,$trap,$damage));
	}
}

?>
