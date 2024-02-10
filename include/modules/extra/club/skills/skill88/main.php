<?php

namespace skill88
{
	function init()
	{
		define('MOD_SKILL88_INFO','club;upgrade;locked;');
		eval(import_module('clubbase'));
		$clubskillname[88] = '激唱';
	}
	
	function acquire88(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost88(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked88(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade88()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(88, $sdata))
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
		if ($skillpoint < 1 || $skillpoint < $skillpara1)
		{
			$log .= '技能点不足。<br>';
			return;
		}
		$mssup = $skillpara1 * 3;
		$mss += $mssup;
		$ss += $mssup;
		$log .= '消耗了<span class="lime b">'.$skillpara1.'</span>点技能点，增加了<span class="yellow b">'.$mssup.'</span>点歌魂上限。<br>';
		$skillpoint -= $skillpara1;
	}
}

?>