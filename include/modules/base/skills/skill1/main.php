<?php

namespace skill1
{
	function init() {}
	
	function acquire1(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost1(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (strpos($pa['inf'],'b')!==false) \skillbase\skill_acquire(1,$pa);
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(1,$pa)) \skillbase\skill_lost(1,$pa);
		$chprocess($pa);
	}
	
	function get_def_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(1,$pd))	//胸部受伤导致战斗防御力下降
			return $chprocess($pa,$pd,$active)*0.75;
		else  return $chprocess($pa,$pd,$active);
	}
	
	function calculate_rest_upsp($rtime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(1)) 	//胸部受伤治疗效果降低
			return round($chprocess($rtime)/2); 
		else  return $chprocess($rtime);
	}
	
	function calculate_rest_uphp($rtime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(1)) 	//胸部受伤治疗效果降低
			return round($chprocess($rtime)/2); 
		else  return $chprocess($rtime);
	}
	
	function get_wound_recover_time()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(1)) 	//胸部受伤解决异常状态时间增加
			return $chprocess() + 30; 
		else  return $chprocess();
	}
}

?>
