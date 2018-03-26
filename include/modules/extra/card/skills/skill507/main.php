<?php

namespace skill507
{
	$skill507_intv = 6;//每被攻击多少次触发
	
	function init() 
	{
		define('MOD_SKILL507_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[507] = '斩杀';
	}
	
	function acquire507(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		init_countdown507($pa);
	}
	
	function lost507(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked507(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$skill507_i = \skillbase\skill_getvalue(507,'i',$pa);
		return $skill507_i <= 0;
	}
	
	function init_countdown507(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill507'));
		\skillbase\skill_setvalue(507,'i',$skill507_intv,$pa);
	}
	
	//被攻击时计数
	function apply_damage(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(507,$pd) && $pa['dmg_dealt']>0 && $pd['hp']>0)
		{
			$skill507_i = \skillbase\skill_getvalue(507,'i',$pd);
			$skill507_i = max(0, $skill507_i - 1);
			\skillbase\skill_setvalue(507,'i',$skill507_i,$pd);
		}
		return $ret;
	}
	

	//解锁后，攻击最终伤害x666，命中率+66%，双穿概率+66%
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(507,$pa) && check_unlocked507($pa))
		{
			$pa['skill507_flag'] = 1;
			eval(import_module('logger'));
			$log.=\battle\battlelog_parser($pa, $pd, $active, "<span class=\"red\"><:pa_name:>出其不意地施展出华丽的攻击！<:pd_name:>觉得这次凶多吉少了。</span><br>");
		}
		$chprocess($pa, $pd, $active);
	}	
	
	function get_hitrate_base(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		$a = 0;
		if (!empty($pa['skill507_flag'])) 
		{
			$a = 66;
		}
		return $ret+$a;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ( !empty($pa['skill507_flag']) )
		{
			$r=Array(666);	
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function get_ex_pierce_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if ( !empty($pa['skill507_flag']) ) {
			$ret += 66;
		}
		return $ret;
	}
	
	function get_attr_pierce_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if ( !empty($pa['skill507_flag']) ) {
			$ret += 66;
		}
		return $ret;
	}
	
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (!empty($pa['skill507_flag'])) 
		{
			$ret = array_merge($ret, array('n','y'));
		}
		return $ret;
	}
	
	//攻击结束时，已解锁变为未解锁状态
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!empty($pa['skill507_flag']))
		{
			init_countdown507($pa);
		}
		$chprocess($pa, $pd, $active);
	}
}

?>