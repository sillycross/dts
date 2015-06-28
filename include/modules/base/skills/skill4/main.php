<?php

namespace skill4
{
	function init() {}
	
	function acquire4(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost4(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (strpos($pa['inf'],'f')!==false) \skillbase\skill_acquire(4,$pa);
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(4,$pa)) \skillbase\skill_lost(4,$pa);
		$chprocess($pa);
	}
	
	function calculate_move_sp_cost()			//移动体力增加
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(4)) 
			return $chprocess()+10;
		else  return $chprocess();
	}
}

?>
