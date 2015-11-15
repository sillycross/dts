<?php

namespace skill414
{
	function init() 
	{
		define('MOD_SKILL414_INFO','club;unique;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[414] = '必中';
	}
	
	function acquire414(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(414,'lvl','0',$pa);
	}
	
	function lost414(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(414,'lvl',$pa);
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function check_unlocked414(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function get_hitrate(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(414,$pa) || $pa['wep_kind']=='D') return $chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)*10000;
	}
}

?>
