<?php

namespace skill488
{	
	function init() 
	{
		define('MOD_SKILL488_INFO','unique;');
		eval(import_module('clubbase'));
		$clubskillname[488] = '后路';
	}
	
	function acquire488(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost488(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
	}
	
	function check_unlocked488(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function attack_finish(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);	
		if (\skillbase\skill_query(488,$pd))
		{
			if($pd['hp'] > 0 && $pd['hp'] <= $pd['mhp'] / 2 && $pa['dmg_dealt']>0){
				if($pd['hp'] + round($pa['dmg_dealt']*0.5) > $pd['mhp']) $hpup = $pd['mhp']-$pd['hp'];
				else $hpup = round($pa['dmg_dealt']*0.5);
				$pd['hp'] += $hpup;
				eval(import_module('logger'));
				$log .= \battle\battlelog_parser($pa, $pd, $active, "然而，<:pd_name:>留有后路，<span class='lime b'>回复了{$hpup}点生命！</span>");
			}
		}
	}
}

?>