<?php

namespace skill525
{

	function init()
	{
		define('MOD_SKILL525_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[525] = '火龙';
	}

	function acquire525(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function lost525(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function check_unlocked525(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}


	//灵系物理伤害为零 投|爆物理伤害吸收转化为攻击
	function check_skill525_proc(&$pa, &$pd, $active, $dmg){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill525','player','logger'));
		if (\skillbase\skill_query(525,$pd) && check_unlocked525($pd) && strstr($pa['wepk'], 'F') != ''){
			$log .=  \battle\battlelog_parser($pa, $pd, $active, '<span class="yellow b">对这只火爆龙的的灵系伤害无效</span><br>');
			return 1;
		}
		elseif (\skillbase\skill_query(525,$pa) && check_unlocked525($pa) && strstr($pd['wepk'], 'F') != ''){
			$log .=  \battle\battlelog_parser($pa, $pd, $active, '<span class="yellow b">对这只火爆龙的灵系伤害无效</span><br>');
			return 1;
		}

		if (\skillbase\skill_query(525,$pd) && check_unlocked525($pd) && ((strstr($pa['wepk'], 'C') != '') || (strstr($pa['wepk'], 'D') != '') || (strstr($pa['wepk'], 'B') != ''))) {
			$rate = 0.3;
			$pd['att'] += $rate * $dmg;
		}
		elseif (\skillbase\skill_query(525,$pa) && check_unlocked525($pa) && ((strstr($pd['wepk'], 'C') != '') || (strstr($pd['wepk'], 'D') != '') || (strstr($pd['wepk'], 'B') != ''))) {
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
		if(check_skill525_proc($pa,$pd,$active, $dmg)){
			$ret = 0;
		}
		return $ret;
	}

	//属性伤害为0
	function calculate_ex_attack_dmg_change(&$pa, &$pd, $active, $tdmg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;

		$ret = $chprocess($pa, $pd, $active, $tdmg);
		if (\skillbase\skill_query(525,$pd) && check_unlocked525($pd)){
			$ret = 0;
		}
		elseif (\skillbase\skill_query(525,$pa) && check_unlocked525($pa)){
			$ret = 0;
		}

		return $ret;
	}

}

?>