<?php

namespace skill273
{
	$effect_factor273 = 10;
	$max_effect273 = 3000;
	$min_chance273 = 0;
	$max_chance273_pc = 40;
	$max_chance273_npc = 70;
	$chance_gain273 = 0.1;
	
	$unlock_lvl273 = 13;
	
	function init() 
	{
		define('MOD_SKILL273_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[273] = '对撞';
	}
	
	function acquire273(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost273(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked273(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill273'));
		return $pa['lvl']>=$unlock_lvl273;
	}
	
	function get_skill273_chance(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill273'));
		$c = $min_chance273 + $pd['wc'] * $chance_gain273;
		if(!$pa['type']) $cmax = $max_chance273_pc;
		else $cmax = $max_chance273_npc;
		$c = min($c, $cmax);
		return $c;
	}
	
	function get_skill273_effect(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill273'));
		if(empty($pd['wep_kind'])) $pd['wep_kind']=\weapon\get_attack_method($pd);
		if($pd['wep_kind'] != 'C') return 0;
		else return min(round(sqrt($pd['wepe'])*10), $max_effect273);
	}
	
	function get_final_dmg_base(&$pa, &$pd, &$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(273,$pd) && check_unlocked273($pd) && 4==$pd['tactic'])
		{
			$chance = get_skill273_chance($pa, $pd, $active);
			if ($pd['club']!=4) $chance=round($chance*3/4);
			if (rand(0,99)<$chance)
			{
				eval(import_module('logger'));
				$dmgdown=get_skill273_effect($pa, $pd, $active);
				if($dmgdown) {
					if ($active)
						$log.='然而，敌人掷出手中的'.$pd['wep'].'，<span class="yellow">抵挡了'.$dmgdown.'点伤害！</span><br>';
					else	$log.='然而，你掷出手中的'.$pd['wep'].'，<span class="yellow">抵挡了'.$dmgdown.'点伤害！</span><br>';
					$pd['wepimp'] = 1;
					\weapon\apply_weapon_imp($pd, $pa, 1-$active);
					$pd['wepimp'] = 0;
					$pa['skill273_dmgdown'] = $dmgdown;
					$ret-=$dmgdown;
					$pa['mult_words_fdmgbs'] = \attack\add_format(-$dmgdown, $pa['mult_words_fdmgbs']);
				}
			}
		}
		return $ret;
	}
}

?>