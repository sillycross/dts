<?php

namespace skill485
{	
	$skill485factors = array(0, 21, 42);
	
	function init() 
	{
		define('MOD_SKILL485_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[485] = '嫉恶';
	}
	
	function acquire485(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(485,'lvl','0',$pa);
	}
	
	function lost485(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(485,'lvl',$pa);
	}
	
	function check_unlocked485(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(485,$pa) && check_unlocked485($pa) && !$pd['type'] && $pd['killnum'] > 0) 
		{
			eval(import_module('logger', 'skill485'));
			$lvl485 = \skillbase\skill_getvalue(485,'lvl',$pa);
			$skill485fac = $pd['killnum'] * $skill485factors[$lvl485];
			if($active){
				$log .= '对方的举止表明，他无疑犯下了累累血债，这让你怒不可遏！你的伤害增加了<span class="red">'.$skill485fac.'</span>%！<br>';
			}else{
				$log .= '你手中的累累血债让'.$pa['name'].'怒不可遏！'.$pa['name'].'的伤害增加了<span class="red">'.$skill485fac.'</span>%！<br>';
			}			
			$r[] = 1 + $skill485fac / 100;
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
//	function apply_total_damage_modifier_seckill(&$pa,&$pd,$active){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$ret = $chprocess($pa,$pd,$active);
//		if (!\skillbase\skill_query(485,$pa) || !check_unlocked485($pa)) return $ret;
//		if(!$pd['type'] && $pd['killnum'] > 0 && $pa['dmg_dealt'] > 0){
//			eval(import_module('logger', 'skill485'));
//			$lvl485 = \skillbase\skill_getvalue(485,'lvl',$pa);
//			$skill485fac = $skill485factors[$lvl485];
//			$dmgup = round($pd['killnum'] * $skill485fac / 100 * $pa['dmg_dealt']);
//			if($active){
//				$log .= '对方的举止表明，他无疑犯下了累累血债，这让你怒不可遏！你的伤害增加了<span class="red">'.$dmgup.'</span>点！<br>';
//			}else{
//				$log .= '你手中的累累血债让'.$pa['name'].'怒不可遏！'.$pa['name'].'的伤害增加了<span class="red">'.$dmgup.'</span>点！<br>';
//			}			
//			$pa['dmg_dealt'] += $dmgup;
//		}		
//	}
}

?>