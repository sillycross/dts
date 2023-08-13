<?php

namespace skill206
{
	function init() 
	{
		define('MOD_SKILL206_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[206] = '爆头';
	}
	
	function acquire206(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(206,'forced',0,$pa);
	}
	
	function lost206(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function get_gunner_skillpoint_need($pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$spcnt=0;
		if (\skillbase\skill_query(201,$pa)){
			eval(import_module('skill201'));
			$spcnt+=\skillbase\skill_getvalue(201,'spent',$pa);
		}
		if (\skillbase\skill_query(202,$pa)){
			eval(import_module('skill202'));
			$spcnt+=\skillbase\skill_getvalue(202,'spent',$pa);
		}
		return (15-$spcnt);
	}
	
	function check_unlocked206(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return (get_gunner_skillpoint_need($pa)<=0 || !empty(\skillbase\skill_getvalue(206,'forced',$pa)));
	}
	
	function apply_total_damage_modifier_seckill(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(206,$pa) && check_unlocked206($pa)) {
			if ( $pa['dmg_dealt'] > $pd['hp']*0.85 && $pa['dmg_dealt'] < $pd['hp'] && \weapon\get_skillkind($pa,$pd,$active) == 'wg'  ){
				$pa['dmg_dealt']=$pd['hp'];
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"red b\">你的攻击直接将敌人爆头！</span><br>";
				else $log .= "<span class=\"red b\">敌人的攻击直接将你爆头！</span><br>";
				$pa['seckill'] = 1;
			}
		}
		$chprocess($pa,$pd,$active);
	}
}

?>