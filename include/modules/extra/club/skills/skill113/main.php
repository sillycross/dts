<?php

namespace skill113
{
	function init()
	{
		define('MOD_SKILL113_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[113] = '洞悉';
	}
	
	function acquire113(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost113(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked113(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=20;
	}
	
	function skill113_get_rate(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$acquired_skills = \skillbase\get_acquired_skill_array($pa);
		$count = 0;
		if (!empty($acquired_skills))
		{
			eval(import_module('clubbase'));
			foreach ($acquired_skills as $skillid)
			{
				if (isset($clubskillname[$skillid]) && (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'hidden;') === false)) $count += 1;
			}
		}
		$r = 1 + 0.03 * $count;
		return $r;
	}
	
	//物穿生效率增加
	function get_ex_pierce_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(113, $pa) && check_unlocked113($pa)) $ret *= skill113_get_rate($pa);
		return $ret;
	}
	
	//属穿生效率增加
	function get_attr_pierce_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(113, $pa) && check_unlocked113($pa)) $ret *= skill113_get_rate($pa);
		return $ret;
	}
	
	//敌人属防失效率增加
	function get_ex_dmg_def_proc_rate(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $key);
		if (\skillbase\skill_query(113, $pa) && check_unlocked113($pa)) $ret = 100 - (100 - $ret) * skill113_get_rate($pa);
		return $ret;
	}
	
	//敌人物防失效率增加
	function get_ex_phy_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(113, $pa) && check_unlocked113($pa)) $ret = 100 - (100 - $ret) * skill113_get_rate($pa);
		return $ret;
	}
	
	//敌人属抹失效率增加
	function get_ex_phy_nullify_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(113, $pa) && check_unlocked113($pa)) $ret = 100 - (100 - $ret) * skill113_get_rate($pa);
		return $ret;
	}
	
	//敌人物抹失效率增加
	function get_ex_dmg_nullify_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(113, $pa) && check_unlocked113($pa)) $ret = 100 - (100 - $ret) * skill113_get_rate($pa);
		return $ret;
	}
	
	//敌人控伤失效率增加
	function get_dmg_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(113, $pa) && check_unlocked113($pa)) $ret = 100 - (100 - $ret) * skill113_get_rate($pa);
		return $ret;
	}
	
}

?>