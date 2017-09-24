<?php

namespace skill485
{	
	function init() 
	{
		define('MOD_SKILL485_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[485] = '嫉恶';
	}
	
	function acquire485(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost485(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked485(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function apply_total_damage_modifier_up(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (!\skillbase\skill_query(485,$pa) || !check_unlocked485($pa)) return $ret;
		if(!$pd['type'] && $pd['killnum'] > 0 && $pa['dmg_dealt'] > 0){
			eval(import_module('logger'));
			$dmgup = round($pd['killnum'] * 13 / 100 * $pa['dmg_dealt']);
			if($active){
				$log .= '对方的举止表明，他无疑犯下了累累血债，这让你怒不可遏！你的伤害增加了<span class="red">'.$dmgup.'</span>点！<br>';
			}else{
				$log .= '你手中的累累血债让'.$pa['name'].'怒不可遏！'.$pa['name'].'的伤害增加了<span class="red">'.$dmgup.'</span>点！<br>';
			}			
			$pa['dmg_dealt'] += $dmgup;
		}		
	}
}

?>