<?php

namespace skill423
{
	function init() 
	{
		define('MOD_SKILL423_INFO','club;unique;locked;');
		eval(import_module('clubbase'));
		$clubskillname[423] = '魔鬼';
	}
	
	function acquire423(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(423,'lvl','0',$pa);
	}
	
	function lost423(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(423,'lvl',$pa);
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function check_unlocked423(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function apply_damage(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(423,$pd) || !check_unlocked423($pd)) return $chprocess($pa,$pd,$active);
		eval(import_module('logger'));
		if ($pa['type']==88){
			$pa['dmg_dealt']=0;
			if ($active) $log .= "<span class=\"yellow\">你的攻击被敌人完全吸收了！</span><br>";
			else $log .= "<span class=\"yellow\">敌人的攻击被你完全吸收了！</span><br>";
		}
		$chprocess($pa,$pd,$active);
	}
}

?>
