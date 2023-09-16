<?php

namespace skill482
{

	function init() 
	{
		define('MOD_SKILL482_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[482] = '梦想<br>天生';
	}
	
	function acquire482(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost482(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked482(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		$ret=$chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(482,$pa) && rand(1,100)<=30 && $pa['wepk']=='WF')
		{
			eval(import_module('logger'));
			if ($active)
				$log.='<span class="red b">你身体中的灵力仿佛与武器融为了一体，打出了爆炸般的伤害！</span><br>';
			else  $log.='<span class="red b">敌人身体中的灵力仿佛与武器融为了一体，打出了爆炸般的伤害！</span><br>';
			$r=Array(2);
		}
		return array_merge($r,$ret);
	}
}

?>
