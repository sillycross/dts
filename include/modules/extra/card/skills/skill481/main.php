<?php

namespace skill481
{
	$stuntime481 = 1000;	

	function init() 
	{
		define('MOD_SKILL481_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[481] = '■■';
	}
	
	function acquire481(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost481(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked481(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//攻击结束时如果命中则做标记
	function attack_finish(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		if(\skillbase\skill_query(481,$pa) && !empty($pa['is_hit']))
		{
			$pa['skill481_flag'] = 1;
		}
	}
	
	//战斗结束时如果有标记，则对方眩晕
	function battle_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		eval(import_module('logger','skill481'));
		
		$b_log_1 = '<span class="yellow b">一股神秘的力量使<:pd_name:>晕眩了'.($stuntime481/1000).'秒，你仿佛感觉获得了新的能量。</span><br>';
		$b_log_2 = '<span class="yellow b">一股神秘的力量使你晕眩了'.($stuntime481/1000).'秒，但你仿佛感觉刚刚完成了一件很有意义的事情。</span><br>';
		
		if(!empty($pa['skill481_flag'])){
			\skill602\set_stun_period($stuntime481,$pd);
			if($active) {
				$log .= str_replace('<:pd_name:>', $pd['name'], $b_log_1);
				$e_log = $b_log_2;
			}else{
				$log .= $b_log_2;
				$e_log = str_replace('<:pd_name:>', $pd['name'], $b_log_1);
			}
		}elseif(!empty($pd['skill481_flag'])){
			\skill602\set_stun_period($stuntime481,$pa);
			if($active) {
				$log .= $b_log_2;
				$e_log = str_replace('<:pd_name:>', $pa['name'], $b_log_1);
			}else{
				$log .= str_replace('<:pd_name:>', $pa['name'], $b_log_1);
				$e_log = $b_log_2;
			}
		}
		if(!empty($e_log)){
			if($active && !$pd['type']) $pd['battlelog'].=$e_log;
			elseif(!$active && !$pa['type']) $pa['battlelog'].=$e_log;
		}
	}
	
//	function get_final_dmg_multiplier(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if (\skillbase\skill_query(481,$pa))
//		{
//			eval(import_module('logger','skill481'));
//			if ($active)
//				$log.='<span class="yellow b">一股神秘的力量使敌人晕眩了'.($stuntime481/1000).'秒，你仿佛感觉获得了新的能量。</span><br>';
//			else  $log.='<span class="yellow b">一股神秘的力量使你晕眩了'.($stuntime481/1000).'秒，但你仿佛感觉刚刚完成了一件很有意义的事情。</span><br>';
//			\skill602\set_stun_period($stuntime481,$pd);
//		}
//		return $chprocess($pa,$pd,$active);
//	}
}

?>