<?php

namespace skill1004
{
	function init() 
	{
		define('MOD_SKILL1004_INFO','unique;');
		eval(import_module('clubbase'));
		$clubskillname[1004] = '难度';
	}
	
	function acquire1004(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(1004,'lvl',0,$pa);//默认0表示不做限制
	}
	
	function lost1004(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked1004(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_skill1004_lvl(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($pa == NULL) {
			eval(import_module('player'));
			$pa = &$sdata;
		}
		if(!\skillbase\skill_query(1004, $pa)) return 0; //无此技能返回0
		return \skillbase\skill_getvalue(1004,'lvl',$pa);
	}
	
	function get_attacker_factor(&$pa, &$pd, $active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(1004, $pa) || !check_unlocked1004($pa)) return 1;
		eval(import_module('skill1004'));
		$skill1004_lvl = round(\skillbase\skill_getvalue(1004,'lvl',$pa));
		if($skill1004_lvl < 0) $skill1004_lvl = 0;
		if($skill1004_lvl > 4) $skill1004_lvl = 4;
		if(!$pd['type']) return $skill1004_dmg_factor_a_pc[$skill1004_lvl];
		else return $skill1004_dmg_factor_a_npc[$skill1004_lvl];
	}
	
	function get_defender_factor(&$pa, &$pd, $active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(1004, $pd) || !check_unlocked1004($pd)) return 1;
		eval(import_module('skill1004'));
		$skill1004_lvl = round(\skillbase\skill_getvalue(1004,'lvl',$pd));
		if($skill1004_lvl < 0) $skill1004_lvl = 0;
		if($skill1004_lvl > 4) $skill1004_lvl = 4;
		if(!$pa['type']) return $skill1004_dmg_factor_d_pc[$skill1004_lvl];
		else return $skill1004_dmg_factor_d_npc[$skill1004_lvl];
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ra = get_attacker_factor($pa, $pd, $active);
		$rd = get_defender_factor($pa, $pd, $active);
		if($ra != 1 || $rd != 1) {
			eval(import_module('logger'));
			$log .= '由于难度调整';
		}
		if($ra != 1) $log .= \battle\battlelog_parser($pa, $pd, $active, '，<span class="yellow b"><:pa_name:>造成的最终伤害变为了'.($ra*100).'%</span>');
		if($rd != 1) $log .= \battle\battlelog_parser($pa, $pd, $active, '，<span class="yellow b"><:pd_name:>受到的最终伤害变为了'.($rd*100).'%</span>');
		if($ra != 1 || $rd != 1) {
			$log .= '！<br>';
		}
		$r=Array($rd, $ra);		
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
}

?>