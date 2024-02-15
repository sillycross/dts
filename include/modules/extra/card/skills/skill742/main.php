<?php

namespace skill742
{
	function init()
	{
		define('MOD_SKILL742_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[742] = '次品';
	}
	
	function acquire742(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(742,'lvl','1',$pa);
	}
	
	function lost742(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(742,'lvl',$pa);
	}
	
	function check_unlocked742(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(742, $pa) && (rand(0,99) < 25))
		{
			$clv = (int)\skillbase\skill_getvalue(742,'lvl',$pa);
			if (($clv == 1) && (strpos($pa['wep'],'电气') !== false))
			{
				eval(import_module('logger'));
				if ($active) $log .= "你因为武器漏电而<span class=\"yellow b\">身体麻痹</span>了！<br>";
				else $log .= "{$pa['name']}因为武器漏电而<span class=\"yellow b\">身体麻痹</span>了！<br>";
				\wound\get_inf('e', $pa);
			}
			elseif (($clv == 2) && (strpos($pa['wep'],'毒性') !== false))
			{
				eval(import_module('logger'));
				if ($active) $log .= "你习惯性地舔了一下自己的武器，然后<span class=\"purple b\">中毒</span>了！<br>";
				else $log .= "{$pa['name']}习惯性地舔了一下自己的武器，然后<span class=\"purple b\">中毒</span>了！<br>";
				\wound\get_inf('p', $pa);
			}
		}
		$chprocess($pa, $pd, $active);
	}
	
}

?>