<?php

namespace skill457
{

	function init() 
	{
		define('MOD_SKILL457_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[457] = '文盲';
	}
	
	function acquire457(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost457(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked457(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_trap_final_damage_modifier_down(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(457,$pd) || mb_strlen($tritm['itm'],'utf-8')<=9) return $chprocess($pa,$pd,$tritm,$damage);
		eval(import_module('logger'));
		$z=$chprocess($pa,$pd,$tritm,$damage);
		if ($z>=$pd['hp'])
		{
			$z=$pd['hp']-1;
			$log .= "<span class=\"yellow b\">面对着又长又难以理解的陷阱名字，你不由地闭上了眼睛…… 这竟然也能减少陷阱伤害？！<br>算了还是不要吐槽了，总之你没被炸死。</span><br>";
		}
		return $z;
	}
}

?>
