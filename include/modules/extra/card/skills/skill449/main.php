<?php

namespace skill449
{
	function init() 
	{
		define('MOD_SKILL449_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[449] = '一指';
	}
	
	function acquire449(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost449(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked449(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ((\skillbase\skill_query(449,$pa))&&(check_unlocked449($pa)))
		{
			eval(import_module('skill449','logger'));
			if ($pd['type']==90){
				if ($active)
					$log.="<span class=\"yellow b\">你对小兵的仇恨使你造成的最终伤害提高了8%！</span><br>";
				else  $log.="<span class=\"yellow b\">敌人对小兵的仇恨使其造成的最终伤害提高了8%！</span><br>";
				$r=Array(1.08);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
}

?>
