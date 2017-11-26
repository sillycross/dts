<?php

namespace skill7
{
	function init() 
	{
		eval(import_module('wound'));
		//受伤状态简称（用于profile显示）
		$infinfo['i'] = '<span class="clan">冻</span>';
		//受伤状态名称动词
		$infname['i'] = '<span class="clan">冻结</span>';
		//受伤状态对应的特效技能编号
		$infskillinfo['i'] = 7;
	}
	
	function acquire7(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost7(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (strpos($pa['inf'],'i')!==false) \skillbase\skill_acquire(7,$pa);
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(7,$pa)) \skillbase\skill_lost(7,$pa);
		$chprocess($pa);
	}
	
	function calculate_move_sp_cost()			//冻结移动体力增加
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(7)) 
			return $chprocess()+20;
		else  return $chprocess();
	}
	
	function calculate_search_sp_cost()			//冻结探索体力增加
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(7)) 
			return $chprocess()+20;
		else  return $chprocess();
	}
	
	function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(7,$pa))		//冻结命中率降低
			$ret *= 0.8;
		return $ret;
	}
	
//	function get_hitrate(&$pa,&$pd,$active)		//冻结命中率降低
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if (\skillbase\skill_query(7,$pa))			
//			return $chprocess($pa,$pd,$active)*0.8;
//		else  return $chprocess($pa,$pd,$active);
//	}
	
	function get_def_multiplier(&$pa,&$pd,$active)	//冻结防御力降低
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(7,$pd))		
			return $chprocess($pa,$pd,$active)*0.9;
		else  return $chprocess($pa,$pd,$active);
	}
	
	function get_att_multiplier(&$pa,&$pd,$active)	//冻结攻击力降低
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(7,$pa)) 
			return $chprocess($pa,$pd,$active)*0.9;
		else  return $chprocess($pa,$pd,$active);
	}
	
	function calculate_active_obbs_multiplier(&$ldata,&$edata)	//冻结先攻率降低（但出于对原版本的兼容，对手冻结不会增加你的先攻率，不然NPC要哭了）
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(7,$ldata)) 
			return $chprocess($ldata,$edata)*0.9;
		else  return $chprocess($ldata,$edata);
	}
	
	function calculate_counter_rate_multiplier(&$pa, &$pd, $active)	//冻结反击率降低
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(7,$pa)) 
			return $ret*0.9;
		else return $ret;
	}
}

?>
