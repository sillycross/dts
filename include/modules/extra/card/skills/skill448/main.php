<?php

namespace skill448
{
	function init() 
	{
		define('MOD_SKILL448_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[448] = '创世';
	}
	
	function acquire448(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost448(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked448(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(448,$pa))&&(check_unlocked448($pa))&&($key=='p'))
		{
			return $chprocess($pa, $pd, $active, $key)*1.1;
		}
		return $chprocess($pa, $pd, $active, $key);
	}
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['is_hit'] && \skillbase\skill_query(448,$pd)&&(check_unlocked448($pd)))
		{
			eval(import_module('logger'));
			if (strpos($pa['inf'],'p')===false)
			{
				$pa['inf'].='p';
				if ($active)
					$log.='<span class="purple b">创世龙的力量使你中毒了！</span><br>';
				else  $log.="<span class=\"purple b\">创世龙的力量使{$pa['name']}中毒了！</span><br>";
			}
		}
		$chprocess($pa,$pd,$active);
	}
}

?>
