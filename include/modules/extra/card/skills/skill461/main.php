<?php

namespace skill461
{
	function init() 
	{
		define('MOD_SKILL461_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[461] = '驱散';
	}
	
	function acquire461(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost461(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked461(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function skill_acquire($skillid, &$pa = NULL, $no_cover=0)	//阻止角色获得带有debuff标签的技能
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\check_skill_info($skillid,'debuff') && \skillbase\skill_query(461,$pa)) 
				return;
		return $chprocess($skillid,$pa,$no_cover);
	}
	
	function assault_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(461,$pa) && ((int)\skillbase\skill_getvalue(461,'lvl',$pa))==1) $pa['inf']='';
		if (\skillbase\skill_query(461,$pd) && ((int)\skillbase\skill_getvalue(461,'lvl',$pd))==1) $pd['inf']='';
		return $chprocess($pa, $pd, $active);
	}
	
}

?>