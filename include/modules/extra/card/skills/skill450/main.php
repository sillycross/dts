<?php

namespace skill450
{
	function init() 
	{
		define('MOD_SKILL450_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[450] = '淘汰';
	}
	
	function acquire450(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost450(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked450(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ((\skillbase\skill_query(450,$pa))&&(check_unlocked450($pa)))
		{
			eval(import_module('skill450','logger'));
			if ($pd['hp']<($pd['mhp']*0.2)){
				if ($active)
					$log.="<span class=\"yellow b\">「淘汰」使你造成的最终伤害提高了15%！</span><br>";
				else  $log.="<span class=\"yellow b\">「淘汰」使敌人造成的最终伤害提高了15%！</span><br>";
				$r=Array(1.15);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(450,$pa) || !check_unlocked450($pa)) return $ret;
		if ($pd['hp']<($pd['mhp']*0.2)){
			return $ret*1.1;
		}else return $ret;
	}
}

?>
