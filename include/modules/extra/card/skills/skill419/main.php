<?php

namespace skill419
{
	
	function init() 
	{
		define('MOD_SKILL419_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[419] = '修仙';
	}
	
	function acquire419(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost419(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked419(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));
		if (($hour>=1)&&($hour<7)) return 1;
		return 0;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ((\skillbase\skill_query(419,$pa))&&(check_unlocked419($pa)))
		{
			eval(import_module('logger'));
			if ($active)
				$log.="<span class=\"yellow b\">「修仙」使你造成的最终伤害提高了12%！</span><br>";
			else  $log.="<span class=\"yellow b\">「修仙」使敌人造成的最终伤害提高了12%！</span><br>";
			$r=Array(1.12);	
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
}

?>
