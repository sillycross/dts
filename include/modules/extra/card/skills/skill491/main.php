<?php

namespace skill491
{	
	function init() 
	{
		define('MOD_SKILL491_INFO','unique;');
		eval(import_module('clubbase'));
		$clubskillname[491] = '空构';
	}
	
	function acquire491(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost491(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked491(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function weapon_strengthen491(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!\skillbase\skill_query(491, $pa) || $pa['sp'] < 10) return false;
		$spdown = rand(round($pa['msp'] / 20), round($pa['msp'] / 10));
		if($spdown > $pa['sp'] - 1) $spdown = $pa['sp'] - 1;
		$pa['sp'] -= $spdown;
		eval(import_module('itemmain'));
		$r = rand(0, $spdown);
		$pa['wepe'] += $r;
		if($nosta !== $pa['weps']) $pa['weps'] += $spdown - $r;
		return true;
	}
	
	function attack_prepare(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(491, $pa)) {
			$ret = weapon_strengthen491($pa);
			if($ret) {
				eval(import_module('logger'));
				$log .= \battle\battlelog_parser($pa, $pd, $active,"<:pa_name:>使用了「空构」，强化了武器！<br>");
			}
		}
	}
}

?>