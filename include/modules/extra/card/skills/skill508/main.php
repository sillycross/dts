<?php

namespace skill508
{
	function init() 
	{
		define('MOD_SKILL508_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[508] = '慢热';
	}
	
	function acquire508(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost508(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked508(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//只要任一方有此技能，双方秒杀阶段都跳过
	function apply_total_damage_modifier_seckill(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(508,$pa) && check_unlocked508($pa)) || (\skillbase\skill_query(508,$pd) && check_unlocked508($pd))){
			return false;
		}
		$chprocess($pa,$pd,$active);
	}
}

?>