<?php

namespace skill515
{
	function init() 
	{
		define('MOD_SKILL515_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[515] = '杀意';
	}
	
	function acquire515(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost515(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked515(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calc_factor515()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		return $deathnum;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(515,$pa) && check_unlocked515($pa)) 
		{
			eval(import_module('logger'));
			$factor515 = calc_factor515();
			if($factor515 > 0) {
				$log .= \battle\battlelog_parser($pa, $pd, $active, '<span class="red b"><:pa_name:>心中具备的「杀戮之力」让最终伤害增加了'.$factor515.'%！</span><br>');
				
				$r=Array(1+$factor515/100);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
}

?>