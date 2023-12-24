<?php

namespace skill902
{
	function init() 
	{
		define('MOD_SKILL902_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[902] = '恶敌';
	}
	
	function acquire902(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(902,'lvl','0',$pa);
	}
	
	function lost902(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked902(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if (\skillbase\skill_query(902, $pa))
		{
			$skill902_lvl = (int)\skillbase\skill_getvalue(902, 'lvl', $pa);
			if ((($pd['type'] == 2) && ($skill902_lvl >= 1)) || (in_array($pd['type'], array(5,6,11,45)) && ($skill902_lvl >= 2)))
			{
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"red b\">「恶敌」使敌人受到的最终伤害降低了15%！</span><br>";
				else $log .= "<span class=\"red b\">「恶敌」使敌人造成的最终伤害降低了15%！</span><br>";
				$r = array(0.85);
			}
		}
		if (\skillbase\skill_query(902, $pd))
		{
			$skill902_lvl = (int)\skillbase\skill_getvalue(902, 'lvl', $pd);
			if ((($pa['type'] == 14) && ($skill902_lvl >= 3)) || (in_array($pa['type'], array(1,9,15,16,20,21,22)) && ($skill902_lvl >= 4)))
			{
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"red b\">「恶敌」使敌人受到的最终伤害增加了25%！</span><br>";
				else $log .= "<span class=\"red b\">「恶敌」使敌人造成的最终伤害增加了25%！</span><br>";
				$r = array(1.25);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}

}

?>