<?php

namespace skill803
{
	function init() 
	{
		define('MOD_SKILL803_INFO','card;feature;');
		eval(import_module('clubbase'));
		$clubskillname[803] = '铁壳';
	}
	
	function acquire803(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost803(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked803(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//不会反击
	function check_can_counter(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(803, $pd)) return 0;
		return $chprocess($pa, $pd, $active);
	}
	
	//不会有无法反击log
	function player_cannot_counter(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(803, $pd)) return;
		return $chprocess($pa, $pd, $active);
	}
	
	//负面状态免疫
	function skill_acquire($skillid, &$pa = NULL, $no_cover=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\check_skill_info($skillid,'debuff') && \skillbase\skill_query(803,$pa)) return;
		return $chprocess($skillid,$pa,$no_cover);
	}
	
	//受伤免疫
	function apply_weapon_inf(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(803, $pd)) return;
		$chprocess($pa, $pd, $active);
	}
	
	//属性异常免疫
	function check_ex_inf_infliction(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(803, $pd)) return;
		return $chprocess($pa, $pd, $active, $key);
	}
	
	//清除异常
	function assault_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(803, $pa)) $pa['inf']='';
		if (\skillbase\skill_query(803, $pd)) $pd['inf']='';
		return $chprocess($pa, $pd, $active);
	}
	
	//先制率为0
	function check_enemy_meet_active(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(803, $edata)) return 1;
		return $chprocess($ldata,$edata);
	}

}

?>