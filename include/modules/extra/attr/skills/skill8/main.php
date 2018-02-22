<?php

namespace skill8
{
	function init() 
	{
		eval(import_module('wound'));
		//受伤状态简称（用于profile显示）
		$infinfo['e'] = '<span class="yellow">麻</span>';
		//受伤状态名称动词
		$infname['e'] = '<span class="yellow">身体麻痹</span>';
		//受伤状态对应的特效技能编号
		$infskillinfo['e'] = 8;
	}
	
	function acquire8(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost8(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (strpos($pa['inf'],'e')!==false) \skillbase\skill_acquire(8,$pa);
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(8,$pa)) \skillbase\skill_lost(8,$pa);
		$chprocess($pa);
	}
	
	function calculate_move_sp_cost()			//麻痹移动体力增加
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(8)) 
			return $chprocess()+5;
		else  return $chprocess();
	}
	
	function calculate_search_sp_cost()			//麻痹探索体力增加
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(8)) 
			return $chprocess()+5;
		else  return $chprocess();
	}
	
	function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(8,$pa))		//麻痹命中率降低
			$ret *= 0.9;
		return $ret;
	}	
	
//	function get_hitrate(&$pa,&$pd,$active)		//麻痹命中率降低
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if (\skillbase\skill_query(8,$pa))			
//			return $chprocess($pa,$pd,$active)*0.9;
//		else  return $chprocess($pa,$pd,$active);
//	}
	
	function calculate_active_obbs_multiplier(&$ldata,&$edata)	//麻痹先攻率降低（但出于对原版本的兼容，对手麻痹不会增加你的先攻率，不然NPC要哭了）
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(8,$ldata)) 
			return $chprocess($ldata,$edata)*0.2;
		else  return $chprocess($ldata,$edata);
	}
	
	function calculate_counter_rate_multiplier(&$pa, &$pd, $active)	//麻痹反击率降低
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(8,$pa)) 
			return $ret*0.2;
		else  return $ret;
	}
}

?>
