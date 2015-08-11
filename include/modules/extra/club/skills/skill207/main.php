<?php

namespace skill207
{
	//回避率提高
	$evagain = Array(0,3,6,9,12,16,20);
	//浮动变化
	$flucgain = Array(0,5,10,15,20,25,30);
	//无视射程概率
	$rangerate = Array(0,20,40,60,80,100,100);
	//升级所需技能点数值
	$upgradecost = Array(3,3,3,4,4,5,-1);
	
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
	
	function get_skill207_evasion_bonus(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill207','player','logger'));
		if (!\skillbase\skill_query(207, $pd) || !check_unlocked207($pd)) return 1;
		if ($pd['wep_kind']!='K') return 1;
		$eva = $evagain[\skillbase\skill_getvalue(207,'lvl',$pd)];
		return 1-$eva/100;
	}
	
	function get_skill207_fluc_bonus(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill207','player','logger'));
		if (!\skillbase\skill_query(207, $pa) || !check_unlocked207($pa)) return 1;
		if ($pa['wep_kind']!='K') return 1;
		$fluc = $flucgain[\skillbase\skill_getvalue(207,'lvl',$pa)];
		return (100+$fluc)/100;
	}
	
	function get_skill207_rangerate(&$pa, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill207','player','logger'));
		if (!\skillbase\skill_query(207, $pa) || !check_unlocked207($pa)) return 0;
		if ($pa['wep_kind']!='K') return 0;
		$ra = $rangerate[\skillbase\skill_getvalue(207,'lvl',$pa)];
		return $ra;
	}
	
	function get_hitrate(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(207,$pd) || !check_unlocked207($pd)) return $chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)*get_skill207_evasion_bonus($pa, $pd, $active);
	}
	
	function get_1st_dmg_factor_l(&$pa,&$pd,$active,$basefluc){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(207,$pa) || !check_unlocked207($pa)) return $chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active,$basefluc)*get_skill207_fluc_bonus($pa, $pd, $active);
	}
	
	function get_1st_dmg_factor_h(&$pa,&$pd,$active,$basefluc){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(207,$pa) || !check_unlocked207($pa)) return $chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active,$basefluc)*get_skill207_fluc_bonus($pa, $pd, $active);
	}
	
	function get_weapon_range_counterer(&$pa, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(207,$pa) || !check_unlocked207($pa)) return $chprocess($pa, $pd, $active);
		if (rand(0,99)<get_skill207_rangerate($pa, $active)) return 233333+$chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active);
	}
	
	function calculate_counter_rate_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(207,$pa) || !check_unlocked207($pa)) return $chprocess($pa, $pd, $active);
		if ($pa['wepk']!='WK') return $chprocess($pa, $pd, $active);
		eval(import_module('skill207'));
		$clv = (int)\skillbase\skill_getvalue(207,'lvl');
		$r=1;
		if ($clv>=6) $r=1.4;
		return $chprocess($pa, $pd, $active)*$r;
	}
}

?>
