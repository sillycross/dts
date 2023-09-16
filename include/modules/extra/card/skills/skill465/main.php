<?php

namespace skill465
{
	function init() 
	{
		define('MOD_SKILL465_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[465] = '圣火';
	}
	
	function acquire465(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost465(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked465(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $key);
		if(\skillbase\skill_query(465,$pa) && ($key=='f' || $key=='u')){
			$ret *=1.5;
			if(!isset($pa['battlelogflag_skill465'])){
				eval(import_module('logger'));
				$log .= \battle\battlelog_parser($pa, $pd, $active,'「圣火」让<:pa_name:>造成的伤害增加了50%！');
				$pa['battlelogflag_skill465'] = 1;
			}
		}
		return $ret;
	}	
}

?>
