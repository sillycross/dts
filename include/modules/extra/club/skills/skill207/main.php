<?php

namespace skill207
{
	//命中率提高
	$accgain = Array(0,2,4,6,8,11,14);
	$rbgain = Array(0,2,4,6,8,10,12);
	//浮动变化
	$flucgain = Array(0,5,10,15,20,25,30);
	//无视射程概率
	$rangerate = Array(0,20,40,60,80,100,100);
	//升级所需技能点数值
	$upgradecost = Array(4,4,4,4,5,5,-1);
	
	function init() 
	{
		define('MOD_SKILL207_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[207] = '直感';
	}
	
	function acquire207(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(207,'lvl','0',$pa);
	}
	
	function lost207(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked207(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade207()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill207','player','logger'));
		if (!\skillbase\skill_query(207))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(207,'lvl');
		$ucost = $upgradecost[$clv];
		if ($clv == -1)
		{
			$log.='你已经升级完成了，不能继续升级！<br>';
			return;
		}
		if ($skillpoint<$ucost) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		$skillpoint-=$ucost; \skillbase\skill_setvalue(207,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function get_skill207_extra_acc_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill207','player','logger'));
		if (!\skillbase\skill_query(207, $pa) || !check_unlocked207($pa)) return 1;
		if (\weapon\get_skillkind($pa,$pd,$active) != 'wk') return 1;
		$accgainrate = $accgain[\skillbase\skill_getvalue(207,'lvl',$pa)];
		return 1+($accgainrate)/100;
	}
	
	function get_skill207_extra_rb_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill207','player','logger'));
		if (!\skillbase\skill_query(207, $pa) || !check_unlocked207($pa)) return 1;
		if (\weapon\get_skillkind($pa,$pd,$active) != 'wk') return 1;
		$rbgainrate = $rbgain[\skillbase\skill_getvalue(207,'lvl',$pa)];
		return 1+($rbgainrate)/100;
	}
	
	function get_rapid_accuracy_loss(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(207,$pa) || !check_unlocked207($pa)) return $chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)*get_skill207_extra_rb_gain($pa, $pd, $active);
	}
	
	function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(207,$pa) || !check_unlocked207($pa)) return $ret;
		return $ret*get_skill207_extra_acc_gain($pa, $pd, $active);
	}
	
	function get_skill207_fluc_bonus(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill207','player','logger'));
		if (!\skillbase\skill_query(207, $pa) || !check_unlocked207($pa)) return 1;
		if (\weapon\get_skillkind($pa,$pd,$active) != 'wk') return 1;
		$fluc = $flucgain[\skillbase\skill_getvalue(207,'lvl',$pa)];
		return (100+$fluc)/100;
	}
	
	function get_skill207_rangerate(&$pa, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill207','player','logger'));
		if (!\skillbase\skill_query(207, $pa) || !check_unlocked207($pa)) return 0;
		$dummy = array();
		if (\weapon\get_skillkind($pa,$dummy,$active) != 'wk') return 0;
		$ra = $rangerate[\skillbase\skill_getvalue(207,'lvl',$pa)];
		return $ra;
	}
	
	function get_weapon_fluc_max_range(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(207,$pa) || !check_unlocked207($pa)) return $chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)*get_skill207_fluc_bonus($pa, $pd, $active);
	}
	
	function check_counterable_by_weapon_range(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(207,$pa) || !check_unlocked207($pa)) return $chprocess($pa, $pd, $active);
		if (rand(0,99)<get_skill207_rangerate($pa, $active) && \weapon\get_weapon_range($pd, 1-$active)!=0) 
			return 1;
		return $chprocess($pa, $pd, $active);
	}
	
	function calculate_counter_rate_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (!\skillbase\skill_query(207,$pa) || !check_unlocked207($pa)) return $ret;
		if (\weapon\get_skillkind($pa,$pd,$active) != 'wk') return $ret;
		eval(import_module('skill207'));
		$clv = (int)\skillbase\skill_getvalue(207,'lvl',$pa);
		$r=1;
		if ($clv>=6) $r=1.3;
		return $ret*$r;
	}
}

?>
