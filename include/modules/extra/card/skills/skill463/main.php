<?php

namespace skill463
{
	function init() 
	{
		define('MOD_SKILL463_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[463] = '藤甲';
	}
	
	function acquire463(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost463(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked463(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(463,$pd)) 
		{
			eval(import_module('logger'));
			$r=Array(0.83);
			if ($active)
				$log.='<span class="yellow b">敌人的藤甲降低了其受到的物理伤害！</span><br>';
			else  $log.='<span class="yellow b">藤甲降低了你受到的物理伤害！</span><br>';
		}
		return array_merge($r,$chprocess($pa, $pd, $active));
	}
	
	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(463,$pd) || ($key!='f' && $key!='u')) return $chprocess($pa, $pd, $active, $key);
		eval(import_module('logger'));
		if ($active)
			$log.='<span class="yellow b">敌人的藤甲烧起来了！烫烫烫烫烫烫烫！</span><br>';
		else  $log.='<span class="yellow b">你的藤甲烧起来了！烫烫烫烫烫烫烫！</span><br>';
		return $chprocess($pa, $pd, $active, $key)*2.5;
	}	
}

?>
