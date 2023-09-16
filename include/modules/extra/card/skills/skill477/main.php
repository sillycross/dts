<?php

namespace skill477
{
	function init() 
	{
		define('MOD_SKILL477_INFO','card;');
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
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(477,$pa) && $pd['ss']<30)
		{
			eval(import_module('logger'));
			if ($active)
				$log.="<span class=\"yellow b\">由于敌人没戏唱了，你的伤害增加了30%！</span><br>";
			else  $log.="<span class=\"yellow b\">由于你没戏唱了，你受到的伤害增加了30%</span><br>";
			$r=Array(1.30);	
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
}

?>