<?php

namespace skill604
{
	
	function init() 
	{
		define('MOD_SKILL604_INFO','hidden;debuff;');
		eval(import_module('clubbase'));
		$clubskillname[604] = '灾厄';
	}
	
	function acquire604(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(604,'start',0,$pa);
		\skillbase\skill_setvalue(604,'end',0,$pa);
	}
	
	function lost604(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_skill604_state(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(604,$pa)) return 0;
		eval(import_module('sys'));
		$e=\skillbase\skill_getvalue(604,'end',$pa);
		if ($now<$e) return 1;
		return 0;
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if (\skillbase\skill_query(604,$sdata))
		{
			eval(import_module('skill604','skillbase'));
			$skill604_start = (int)\skillbase\skill_getvalue(604,'start'); 
			$skill604_end = (int)\skillbase\skill_getvalue(604,'end'); 
			$z=Array(
				'disappear' => 1,
				'clickable' => 0,
				'hint' => '你已经大难临头了！',
			);
			if ($now<$skill604_end)
			{
				$z['style']=1;
				$z['totsec']=$skill604_end-$skill604_start;
				$z['nowsec']=$now-$skill604_start;
				\bufficons\bufficon_show('img/skill604.gif',$z);
			}
			else 
			{
				\skillbase\skill_lost(604);
			}
		}
		$chprocess();
	}
	
	//命中率降低30%
	function get_hitrate_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(604,$pa) && 1 == check_skill604_state($pa))
		{
			$r = 0.7;
		}
		return $chprocess($pa, $pd, $active)*$r;
	}
	
	//先制率降低30%
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(604,$ldata) && 1 == check_skill604_state($ldata)) 
		{
			$r = 0.7;
		}elseif(\skillbase\skill_query(604,$edata) && 1 == check_skill604_state($edata))
		{
			$r = 1/0.7;
		}
		if($r != 1) $ldata['active_words'] = \attack\multiply_format($r, $ldata['active_words'],0);
		return $chprocess($ldata,$edata)*$r;
	}
	
	//物防成功率降低30%
	function get_ex_phy_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(604,$pd) && 1 == check_skill604_state($pd)) {
			$ret *= 0.7;
		}
		return $ret;
	}
	
	//属防
	function get_ex_dmg_def_proc_rate(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $key);
		if(\skillbase\skill_query(604,$pd) && 1 == check_skill604_state($pd)) {
			$ret *= 0.7;
		}
		return $ret;
	}
	
	//物抹
	function get_ex_phy_nullify_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(604,$pd) && 1 == check_skill604_state($pd)) {
			$ret *= 0.7;
		}
		return $ret;
	}
	
	//属抹
	function get_ex_dmg_nullify_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(604,$pd) && 1 == check_skill604_state($pd)) {
			$ret *= 0.7;
		}
		return $ret;
	}
	
	//伤害制御效果发生率
	function get_dmg_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(604,$pd) && 1 == check_skill604_state($pd)) {
			$ret *= 0.7;
		}
		return $ret;
	}
}

?>