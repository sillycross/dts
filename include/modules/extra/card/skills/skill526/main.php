<?php

namespace skill526
{

	function init()
	{
		define('MOD_SKILL526_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[526] = '水龙';
	}

	function acquire526(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function lost526(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function check_unlocked526(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}


	//灵系物理伤害为零 斩|殴物理伤害吸收转化为攻击
	function check_skill526_proc(&$pa, &$pd, $active, $dmg){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill526','player','logger'));
		if (\skillbase\skill_query(526,$pd) && check_unlocked526($pd) && strstr($pa['wepk'], 'F') != ''){
			$log .=  \battle\battlelog_parser($pa, $pd, $active, '<span class="yellow b">对这只水溅龙的灵系伤害无效</span><br>');
			return 1;
		}
		elseif (\skillbase\skill_query(526,$pa) && check_unlocked526($pa) && strstr($pd['wepk'], 'F') != ''){
			$log .=  \battle\battlelog_parser($pa, $pd, $active, '<span class="yellow b">对对这只水溅龙的灵系伤害无效</span><br>');
			return 1;
		}

		if (\skillbase\skill_query(526,$pd) && check_unlocked526($pd) && ((strstr($pa['wepk'], 'K') != '') || (strstr($pa['wepk'], 'P') != '') || (strstr($pa['wepk'], 'N') != ''))) {
			$rate = 0.3;
			$pd['att'] += $rate * $dmg;
		}
		elseif (\skillbase\skill_query(526,$pa) && check_unlocked526($pa) && ((strstr($pd['wepk'], 'K') != '') || (strstr($pd['wepk'], 'P') != '') || (strstr($pd['wepk'], 'N') != ''))) {
			$rate = 0.2;
			$pa['att'] += $rate * $dmg;
			//玩家部分暂定
		}
		return 0;
	}

	function get_physical_dmg_change(&$pa, &$pd, $active, $dmg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $dmg);
		if(check_skill526_proc($pa,$pd,$active, $dmg)){
			$ret = 0;
		}
		return $ret;
	}

	//属性伤害为0
	function calculate_ex_attack_dmg_change(&$pa, &$pd, $active, $tdmg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $tdmg);
		if (\skillbase\skill_query(526,$pd) && check_unlocked526($pd)){
			$ret = 0;
		}

		return $ret;
	}

}

?>