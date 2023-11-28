<?php

namespace skill706
{
	function init() 
	{
		define('MOD_SKILL706_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[706] = '裁决';
	}
	
	function acquire706(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost706(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked706(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_sk706_dmggain(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$flag_b = 0;
		$flag_w = 0;
		if ($pa['type'])
		{
			eval(import_module('npc'));
			$pa_typeinfo = $npc_typeinfo[$pa['type']];
			if (strpos($pa_typeinfo, "黑") !== false) $flag_b = 1;
			if (strpos($pa_typeinfo, "白") !== false) $flag_w = 1;
		}
		foreach (array('name','cardname','wep','arb','arh','ara','arf','art','itm1','itm2','itm3','itm4','itm5','itm6') as $key)
		{
			if ($flag_b && $flag_w) break;
			if ((!$flag_b) && (strpos($pa[$key], "黑") !== false)) $flag_b = 1;
			if ((!$flag_w) && (strpos($pa[$key], "白") !== false)) $flag_w = 1;
		}
		$r = 0;
		if ($flag_b) $r += 70;
		if ($flag_w) $r -= 30;
		return $r;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if ((\skillbase\skill_query(706,$pa))&&(check_unlocked706($pa)))
		{
			$sk706_dmggain = get_sk706_dmggain($pd);
			eval(import_module('logger'));
			if ($sk706_dmggain > 0)
			{
				if ($active) $log.="<span class=\"yellow b\">「裁决」使你造成的最终伤害增加了{$sk706_dmggain}%！</span><br>";
				else $log.="<span class=\"yellow b\">「裁决」使敌人造成的最终伤害增加了{$sk706_dmggain}%！</span><br>";
				$r = array(1 + $sk706_dmggain / 100);
			}
			elseif ($sk706_dmggain < 0)
			{
				$sk706_dmgreduce = -$sk706_dmggain;
				if ($active) $log.="<span class=\"yellow b\">「裁决」使你造成的最终伤害降低了{$sk706_dmgreduce}%！</span><br>";
				else $log.="<span class=\"yellow b\">「裁决」使敌人造成的最终伤害降低了{$sk706_dmgreduce}%！</span><br>";
				$r = array(1 + $sk706_dmggain / 100);
			}
		}
		return array_merge($r, $chprocess($pa,$pd,$active));
	}
	
}

?>