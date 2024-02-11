<?php

namespace skill108
{
	function init()
	{
		define('MOD_SKILL108_INFO','club;feature;');
		eval(import_module('clubbase'));
		$clubskillname[108] = '智识';
		$clubdesc_h[27] .= '<br>理智值不低于4时每次升级获得2-4点全系熟练度<br>理智值低于4时先攻率和造成伤害增加，但无法获得经验值';
	}
	
	function acquire108(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost108(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked108(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \skillbase\skill_query(107, $pa);
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(108,$pa) && check_unlocked108($pa))
		{
			$sanity = (int)\skillbase\skill_getvalue(107,'sanity',$pa);
			if ($sanity >= 4)
			{
				$pa['wp'] += rand(2,4);
				$pa['wk'] += rand(2,4);
				$pa['wc'] += rand(2,4);
				$pa['wg'] += rand(2,4);
				$pa['wd'] += rand(2,4);
				$pa['wf'] += rand(2,4);
			}
		}
		$chprocess($pa);
	}
	
	function getexp($v, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (empty($pa))
		{
			eval(import_module('player'));
			if (\skillbase\skill_query(108,$sdata) && check_unlocked108($sdata))
			{
				$sanity = (int)\skillbase\skill_getvalue(107,'sanity',$sdata);
				if ($sanity < 4) return 0;
			}
		}
		elseif (\skillbase\skill_query(108,$pa) && check_unlocked108($pa))
		{
			$sanity = (int)\skillbase\skill_getvalue(107,'sanity',$pa);
			if ($sanity < 4) return 0;
		}
		return $chprocess($v, $pa);
	}
	
	//先制率增加
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(108, $ldata) && check_unlocked108($ldata))
		{
			$sanity = (int)\skillbase\skill_getvalue(107,'sanity',$ldata);
			if ($sanity < 4) $r *= 1.1;
		}
		if (\skillbase\skill_query(108, $edata) && check_unlocked108($edata))
		{
			$sanity = (int)\skillbase\skill_getvalue(107,'sanity',$edata);
			if ($sanity < 4) $r /= 1.1;
		}
		if($r != 1) $ldata['active_words'] = \attack\multiply_format($r, $ldata['active_words'],0);
		return $chprocess($ldata,$edata)*$r;
	}
	
	//最终伤害变化
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if (\skillbase\skill_query(108, $pa) && check_unlocked108($pa))
		{
			$sanity = (int)\skillbase\skill_getvalue(107,'sanity',$pa);
			if ($sanity < 4)
			{
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"yellow b\">「智识」使你造成的伤害增加了20%！</span><br>";
				else $log .= "<span class=\"yellow b\">「智识」使{$pa['name']}造成的伤害增加了20%！</span><br>";
				$r[] = 1.2;
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
}

?>