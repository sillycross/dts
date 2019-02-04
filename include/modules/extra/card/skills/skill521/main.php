<?php

namespace skill521
{

	function init()
	{
		define('MOD_SKILL521_INFO','card;unique;locked;feature;');
		eval(import_module('clubbase'));
		$clubskillname[521] = '草精';
	}

	function acquire521(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function lost521(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function check_unlocked521(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}


	//非灵系物理伤害为零
	function check_skill521_proc(&$pa, &$pd, $active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill521','player','logger'));
		if ($active && (strstr($pa['wepk'], 'F') == '')){
			$log .=  \battle\battlelog_parser($pa, $pd, $active, '<span class="yellow b"><:pa_name:>的物理伤害无效</span><br>');
			$r = 1;
			return $r;
		}
		return 0;
	}

	function get_physical_dmg_change(&$pa, &$pd, $active, $dmg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;

		if(check_skill521_proc($pa,$pd,$active)){
			$dmg = 0;
		}
		return $dmg;
	}

	//毒系伤害12倍
	function calculate_ex_attack_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;

		$r=Array();
		if (\skillbase\skill_query(521,$pd) && check_unlocked521($pd) &&( strstr($pa['wepsk'], 'p') != '')){
			$r = Array(12);
		}

		return array_merge($r,$chprocess($pa,$pd,$active));
	}

}

?>
