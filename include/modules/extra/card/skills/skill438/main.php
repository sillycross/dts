<?php

namespace skill438
{
	
	function init() 
	{
		define('MOD_SKILL438_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[438] = '挽鸽';
	}
	
	function acquire438(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill438'));
		$t438=$now-$starttime;
		$r438=115;
		if ($t438>300) $r438=125;
		if ($t438>600) $r438=150;
		if ($t438>900) $r438=175;
		if ($t438>1200) $r438=200;
		\skillbase\skill_setvalue(438,'rate',$r438,$pa);
	}
	
	function lost438(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked438(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ((\skillbase\skill_query(438,$pa))&&(check_unlocked438($pa)))
		{
			eval(import_module('logger','skill438'));
			$r438=\skillbase\skill_getvalue(438,'rate',$pa);
			$r=Array(($r438/100));	
			$r438-=100;
			if ($active)
				$log.="<span class=\"yellow b\">「挽鸽」使你造成的最终伤害提高了{$r438}%！</span><br>";
			else  $log.="<span class=\"yellow b\">「挽鸽」使敌人造成的最终伤害提高了{$r438}%！</span><br>";
			
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
}

?>
