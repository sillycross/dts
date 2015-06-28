<?php

namespace skill3
{
	function init() {}
	
	function acquire3(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost3(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (strpos($pa['inf'],'a')!==false) \skillbase\skill_acquire(3,$pa);
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(3,$pa)) \skillbase\skill_lost(3,$pa);
		$chprocess($pa);
	}
	
	function get_att_multiplier(&$pa,&$pd,$active)	//手部受伤攻击力下降
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(3,$pa)) 
			return $chprocess($pa,$pd,$active)*0.75;
		else  return $chprocess($pa,$pd,$active);
	}
	
	function calculate_search_sp_cost()			//探索体力增加
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(3)) 
			return $chprocess()+10;
		else  return $chprocess();
	}
}

?>
