<?php

namespace skill256
{
	function init() 
	{
		define('MOD_SKILL256_INFO','club;feature;');
		eval(import_module('clubbase'));
		$clubskillname[256] = '拳法';
		$clubdesc_h[19] = $clubdesc_a[19] = '开局获得50点殴系熟练度<br>空手作战时相当于持有殴系熟练度数值的武器';
	}
	
	function acquire256(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['wp']+=50;
	}
	
	function lost256(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked256(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_external_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		$ret = $chprocess($pa, $pd, $active);
		if ($pa['wep_kind']=='N' && \skillbase\skill_query(256,$pa) && check_unlocked256($pa))
			$ret += round($pa[$skillinfo['N']]*1/3);
		return $ret;
	}
}

?>