<?php

namespace skill478
{
	function init() 
	{
		define('MOD_SKILL478_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[478] = '悲运';
	}
	
	function acquire478(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost478(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked478(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	//悲运：命中率、回避率、反击率都下降10%，受伤和异常率上升10%
	
	function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=1;
		if (\skillbase\skill_query(478,$pa)) $r*=0.9;//命中率下降10%
		if (\skillbase\skill_query(478,$pd)) $r*=1.1;//回避率下降10%（其实是按敌方命中率上升10%算的，差别应该不是很大）
		//eval(import_module('logger'));$log .= "命中率变动：$r<br>";
		return $chprocess($pa, $pd, $active)*$r;
	}
	
	function calculate_counter_rate_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=1;
		if (\skillbase\skill_query(478,$pa)) $r*=0.9;
		//eval(import_module('logger'));$log .= "反击率变动：$r<br>";
		return $chprocess($pa, $pd, $active)*$r;
	}
	
	function calculate_inf_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=1;
		if (\skillbase\skill_query(478,$pd)) $r*=1.1;
		//eval(import_module('logger'));$log .= "受伤概率变动：$r<br>";
		return $chprocess($pa, $pd, $active)*$r;
	}
	
	function get_ex_inf_rate_modifier(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$a=0;
		if (\skillbase\skill_query(478,$pd)) $a += 10;
		//eval(import_module('logger'));$log .= "异常概率加值：$a<br>";
		return $chprocess($pa,$pd,$active,$key)+$a;
	}
}

?>