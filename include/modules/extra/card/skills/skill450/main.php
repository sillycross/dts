<?php

namespace skill450
{
	function init() 
	{
		define('MOD_SKILL450_INFO','club;unique;locked;');
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
					$log.="<span class=\"yellow\">「淘汰」使你造成的最终伤害提高了50%！</span><br>";
				else  $log.="<span class=\"yellow\">「淘汰」使敌人造成的最终伤害提高了50%！</span><br>";
				$r=Array(1.5);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function get_hitrate(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(450,$pa) || !check_unlocked450($pa)) return $chprocess($pa, $pd, $active);
		if ($pd['hp']<($pd['mhp']*0.2)){
			return $chprocess($pa, $pd, $active)*1.3;
		}else return $chprocess($pa, $pd, $active);
	}
}

?>
