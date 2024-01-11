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
	
	$skill273stateinfo=Array(1=>'关闭', 2=>'开启');
	
	function init() 
	{
		define('MOD_SKILL273_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[273] = '对撞';
	}
	
	function acquire273(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(273,'choice','1',$pa);
	}
	
	function lost273(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(273,'choice',$pa);
	}
	
	function check_unlocked273(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill273'));
		return $pa['lvl']>=$unlock_lvl273;
	}
	
	function upgrade273()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(273) || !check_unlocked273($sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$val = (int)get_var_input('skillpara1');
		if ($val<1 || $val>2)
		{
			$log .= '参数不合法。<br>';
			return;
		}
		\skillbase\skill_setvalue(273,'choice',$val);
		if(1==$val) $log.='现在不会触发「对撞」。<br>';
		else $log.='现在会自动触发「对撞」。<br>';
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
		//if(empty($pd['wep_kind'])) $pd['wep_kind']=\weapon\get_attack_method($pd);
		if(\weapon\get_skillkind($pd,$pa,1-$active) != 'wc') return 0;
		else return min(round(sqrt($pd['wepe'])*10), $max_effect273);
	}
	
	function get_final_dmg_base(&$pa, &$pd, &$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(273,$pd) && check_unlocked273($pd) && 2==\skillbase\skill_getvalue(273,'choice',$pd))
		{
			$chance = get_skill273_chance($pa, $pd, $active);
			if ($pd['club']!=4) $chance=round($chance*3/4);
			if (rand(0,99)<$chance)
			{
				eval(import_module('logger'));
				$dmgdown=get_skill273_effect($pa, $pd, $active);
				if($dmgdown) {
					$wepstr = substr($pd['wepk'],1,1) == 'B' ? '使用'.$pd['wep'].'射出箭' : '掷出手中的'.$pd['wep'];
					if ($active)
						$log.='然而，敌人'.$wepstr.'，<span class="yellow b">抵挡了'.$dmgdown.'点伤害！</span><br>';
					else	$log.='然而，你'.$wepstr.'，<span class="yellow b">抵挡了'.$dmgdown.'点伤害！</span><br>';
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