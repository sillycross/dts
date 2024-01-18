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
		if ($skillpara1 <= 1)
		{
			$log .= '输入参数错误！<br>';
			return;
		}
		if ($rage < 2 || $rage < $skillpara1)
		{
			$log .= '怒气不足。<br>';
			return;
		}
		$mssup = floor($skillpara1 * 0.5);
		$ss += $mssup;
		$log .= '消耗了<span class="lime b">'.$skillpara1.'</span>点怒气，获得了<span class="yellow b">'.$mssup.'</span>点歌魂。<br>';
		$rage -= $skillpara1;
	}
}

?>