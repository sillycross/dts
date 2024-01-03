<?php

namespace skill83
{
	function init() 
	{
		define('MOD_SKILL83_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[83] = '尊严';
	}
	
	function acquire83(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost83(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked83(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function check_wepk_debuff83($owep, $owepk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = 90;
		if('WN' == $owepk || strpos($owep,'拳')!==false) $ret = 0;
		elseif('WP' == $owepk || 'WK' == $owepk) $ret = 50;
		return $ret;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(83,$pa) && check_unlocked83($pa)){
			$pa['skill83_owep'] = $pa['wep'];
			$pa['skill83_owepk'] = $pa['wepk'];
		}
		return $chprocess($pa, $pd, $active);
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ( \skillbase\skill_query(83,$pa) && check_unlocked83($pa) && check_wepk_debuff83($pa['skill83_owep'],$pa['skill83_owepk']) )
		{
			eval(import_module('logger'));
			$rr = check_wepk_debuff83($pa['skill83_owep'],$pa['skill83_owepk']);
			if ($active)
				$log.="<span class=\"yellow b\">「尊严」使你造成的最终伤害降低了{$rr}%！</span><br>";
			else  $log.="<span class=\"yellow b\">「尊严」使敌人造成的最终伤害降低了{$rr}%！</span><br>";
			$r=Array(1-$rr/100);	
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
}

?>