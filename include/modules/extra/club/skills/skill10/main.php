<?php

namespace skill10
{
	function init() 
	{
		define('MOD_SKILL10_INFO','club;upgrade;locked;');
	}
	
	function acquire10(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost10(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked10(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade10()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(10))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$skillpara1 = (int)get_var_input('skillpara1');
		if ($skillpara1 <= 0)
		{
			$log.='技能点指令错误！<br>';
			return;
		}
		if ($skillpoint<1 || $skillpoint < $skillpara1) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		$dice = $skillpara1 * 2;
		$mhp += $dice; $hp += $dice;
		$log.='消耗了<span class="lime b">'.$skillpara1.'</span>点技能点，你的生命上限提升了<span class="yellow b">'.$dice.'</span>点。<br>';
		$skillpoint-=$skillpara1;
	}
	
}

?>