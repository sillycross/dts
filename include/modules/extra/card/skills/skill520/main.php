<?php

namespace skill520
{
	$skill520_factor = 50;
	$skill520_debufftime = 180;
	
	function init() 
	{
		define('MOD_SKILL520_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[520] = '@v@';
	}
	
	function acquire520(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost520(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked520(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//效果1：主动攻击击杀数0的角色时伤害大幅下降
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ( \skillbase\skill_query(520,$pa) && check_unlocked520($pa) && empty($pa['is_counter']) && empty($pd['killnum']))
		{
			eval(import_module('logger','skill520'));
			$log .= \battle\battlelog_parser($pa, $pd, $active, "<span class=\"yellow b\">「纯洁」使<:pa_name:>造成的最终伤害降低了{$skill520_factor}%！</span><br>");

			$r=Array(1-$skill520_factor/100);	
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	//效果2：击杀你的敌人获得DEBUFF
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(520,$pd) && check_unlocked520($pd))
		{
			eval(import_module('logger','skill520','sys'));	
			$log .= \battle\battlelog_parser($pa, $pd, $active, '<span class="red b"><:pa_name:>击杀<:pd_name:>的行为触怒了<:pd_name:>身上寄宿着的梦魇之力！</span><br>');	
			//击杀者获得灾厄DEBUFF
			\skillbase\skill_acquire(604,$pa);
			\skillbase\skill_setvalue(604,'start',$now,$pa);
			\skillbase\skill_setvalue(604,'end',$now + $skill520_debufftime,$pa);
		}
	}
}

?>