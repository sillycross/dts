<?php

namespace skill739
{
	function init()
	{
		define('MOD_SKILL739_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[739] = '蓄势';
	}
	
	function acquire739(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost739(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked739(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade739()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(739, $sdata))
		{
			$log .= '你没有这个技能！<br>';
			return;
		}
		$skillpara1 = (int)get_var_input('skillpara1');
		if ($skillpara1 <= 0)
		{
			$log .= '输入参数错误！<br>';
			return;
		}
		if ($ss < 1 || $ss < $skillpara1)
		{
			$log .= '歌魂不足。<br>';
			return;
		}
		$ss -= $skillpara1;
		$mss += $skillpara1;
		$log .= '消耗了<span class="lime b">'.$skillpara1.'</span>点歌魂，增加了<span class="yellow b">'.$skillpara1.'</span>点空歌魂上限。<br>';
	}
	
}

?>