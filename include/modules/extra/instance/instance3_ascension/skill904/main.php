<?php

namespace skill904
{
	$sk904_dmggain = array(0,10,20,35);
	
	function init() 
	{
		define('MOD_SKILL904_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[904] = '脆弱';
	}
	
	function acquire904(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(904,'lvl','0',$pa);
	}
	
	function lost904(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked904(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_sk904_dmggain(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill904'));
		$sk904_lvl = (int)\skillbase\skill_getvalue(904,'lvl',$pa);
		return $sk904_dmggain[$sk904_lvl];
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if (\skillbase\skill_query(904, $pd))
		{
			$sk904_dmggain = get_sk904_dmggain($pd);
			if ($sk904_dmggain > 0)
			{
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"red b\">「脆弱」使敌人受到的最终伤害增加了{$sk904_dmggain}%！</span><br>";
				else $log .= "<span class=\"red b\">「脆弱」使你受到的最终伤害增加了{$sk904_dmggain}%！</span><br>";
				$r = array(1 + $sk904_dmggain / 100);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}

}

?>