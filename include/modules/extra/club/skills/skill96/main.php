<?php

namespace skill96
{
	function init()
	{
		define('MOD_SKILL96_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[96] = '魂音';
	}
	
	function acquire96(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost96(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(96, 'bufftime', $pa);
		\skillbase\skill_delvalue(96, 'expire', $pa);
		\skillbase\skill_delvalue(96, 'type', $pa);
		\skillbase\skill_setvalue(96, 'effect', $pa);
	}
	
	function check_unlocked96(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//最终伤害变化
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if (\skillbase\skill_query(96, $pa) && check_unlocked96($pa))
		{
			$skill96_type = \skillbase\skill_getvalue(96, 'type', $pa);
			if (!empty($skill96_type) && ($skill96_type[0] == '1'))
			{
				eval(import_module('logger'));
				$skill96_effect = (int)\skillbase\skill_getvalue(96, 'effect', $pa);
				$dmggain = 20 + round(0.1 * $skill96_effect);
				if ($active) $log .= "<span class=\"yellow b\">「魂音」使你造成的伤害增加了{$dmggain}%！</span><br>";
				else $log .= "<span class=\"yellow b\">「魂音」使{$pa['name']}造成的伤害增加了{$dmggain}%！</span><br>";
				$r[] = 1 + $dmggain / 100;
			}
		}
		if (\skillbase\skill_query(96, $pd) && check_unlocked96($pd)) 
		{
			$skill96_type = \skillbase\skill_getvalue(96, 'type', $pd);
			if (!empty($skill96_type) && ($skill96_type[1] == '3'))
			{
				eval(import_module('logger'));
				$skill96_effect = (int)\skillbase\skill_getvalue(96, 'effect', $pd);
				$dmgdown = 30 + round(0.1 * $skill96_effect);
				if ($active) $log .= "<span class=\"yellow b\">「魂音」使{$pd['name']}受到的伤害降低了{$dmgdown}%！</span><br>";
				else $log .=" <span class=\"yellow b\">「魂音」使你受到的伤害降低了{$dmgdown}%！</span><br>";
				$r[] = 1 - $dmggain / 100;
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	//先制率增加
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(96,$ldata) && check_unlocked96($ldata))
		{
			$skill96_type = \skillbase\skill_getvalue(96, 'type', $ldata);
			if (!empty($skill96_type) && ($skill96_type[0] == '2'))
			{
				$skill96_effect = (int)\skillbase\skill_getvalue(96, 'effect', $ldata);
				$r *= 1 + round(0.06 * $skill96_effect) / 100;
			}
		}
		if (\skillbase\skill_query(96,$edata) && check_unlocked96($edata))
		{
			$skill96_type = \skillbase\skill_getvalue(96, 'type', $edata);
			if (!empty($skill96_type) && ($skill96_type[0] == '2'))
			{
				$skill96_effect = (int)\skillbase\skill_getvalue(96, 'effect', $edata);
				$r /= 1 + round(0.06 * $skill96_effect) / 100;
			}
		}
		if($r != 1) $ldata['active_words'] = \attack\multiply_format($r, $ldata['active_words'],0);
		return $chprocess($ldata,$edata)*$r;
	}
	
	//获得升血和激奏3
	function get_ex_def_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(96, $pd) && check_unlocked96($pd))
		{
			$skill96_type = \skillbase\skill_getvalue(96, 'type', $pd);
			if (!empty($skill96_type))
			{
				if ($skill96_type[0] == '3')
				{
					$skill96_effect = (int)\skillbase\skill_getvalue(96, 'effect', $pd);
					$hu_e = 7 * $skill96_effect - 500;
					array_push($ret,'^hu'.$hu_e);
				}
				if ($skill96_type[1] == '1') array_push($ret,'^sa3');
			}
		}
		return $ret;
	}
	
	//获得音爆
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(96, $pa) && check_unlocked96($pa))
		{
			$skill96_type = \skillbase\skill_getvalue(96, 'type', $pa);
			if (!empty($skill96_type) && ($skill96_type[1] == '2')) array_push($ret,'t');
		}
		return $ret;
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if (\skillbase\skill_query(96,$sdata) && check_unlocked96($sdata))
		{
			$skill96_buff_time = \skillbase\skill_getvalue(96, 'bufftime', $sdata);
			$skill96_time = $skill96_buff_time - (\skillbase\skill_getvalue(96,'expire') - $now);
			$z=Array(
				'disappear' => 0,
			);
			if ($skill96_time < $skill96_buff_time)
			{
				$z['clickable'] = 1;
				$z['style']=1;
				$z['totsec']=$skill96_buff_time;
				$z['nowsec']=$skill96_time;
				$skill96_rm = $skill96_buff_time-$skill96_time;
				$z['hint'] = "状态「魂音」";
			}
			else
			{
				$z['clickable'] = 0;
				$z['style']=3;
				$z['activate_hint'] = "状态「魂音」生效时间已经结束";
				\skillbase\skill_lost(96);
			}
			\bufficons\bufficon_show('img/skill96.gif',$z);
		}
		$chprocess();
	}
	
}

?>