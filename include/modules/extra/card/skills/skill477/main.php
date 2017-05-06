<?php

namespace skill477
{
	function init() 
	{
		define('MOD_SKILL477_INFO','club;unique;');
		eval(import_module('clubbase'));
		$clubskillname[477] = '忘我';
	}
	
	function acquire477(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost477(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked477(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(477,$pa) && $pd['ss']<30)
		{
			eval(import_module('logger'));
			$d = round($pa['dmg_dealt']*0.3);
			if ($active) $log.='<span class="yellow">由于敌人没戏唱了，你的伤害增加了'.$d.'点！</span><br>';
			else $log.='<span class="yellow">由于你没戏唱了，你受到的伤害增加了'.$d.'点！</span><br>';
			$pa['dmg_dealt']+=$d;
		}
		$chprocess($pa, $pd, $active);
	}
}

?>