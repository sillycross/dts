<?php

namespace skill245
{
	function init() 
	{
		define('MOD_SKILL245_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[245] = '钦定';
	}
	
	function acquire245(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost245(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked245(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function calculate_weather_itemfind_obbs()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(245)) return abs($chprocess()); else return $chprocess();
	}
	
	function calculate_weather_meetman_obbs(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(245)) return abs($chprocess($edata)); else return $chprocess($edata);
	}
	
	function calculate_weather_active_obbs(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(245,$ldata)) return abs($chprocess($ldata,$edata)); else return $chprocess($ldata,$edata);
	}
	
	function calculate_weather_attack_modifier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(245,$pa)) return abs($chprocess($pa,$pd,$active)); else return $chprocess($pa,$pd,$active);
	}
	
	function calculate_weather_defend_modifier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(245,$pd)) return abs($chprocess($pa,$pd,$active)); else return $chprocess($pa,$pd,$active);
	}
	
	function deal_hailstorm_weather_damage()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(245)) {
			$chprocess();
			return;
		}
		eval(import_module('sys','player','logger'));
		$heal = \weather\calculate_hailstorm_weather_damage();
		$hpup = min($heal, $mhp-$hp); $hpup = max($hpup, 0);
		$hp+=$hpup;
		$log .= "你被<span class=\"blue b\">冰雹</span>击中了，但是你不仅没有受到伤害，还回复了<span class=\"lime b\">$heal</span>点生命！<br>";
	}
	
	function apply_tornado_weather_effect()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(245)) {
			$chprocess();
			return;
		}
		eval(import_module('logger'));
		$log .= "<span class=\"lime b\">即使强烈的龙卷风已经把目力所及的一切都搅的一片狼藉，你竟没有受到任何影响便到达了目的地。</span><br>";
	}
}

?>
