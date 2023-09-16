<?php

namespace skill439
{
	function init() 
	{
		define('MOD_SKILL439_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[439] = '面子';
	}
	
	function acquire439(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(439,'type','X',$pa);
	}
	
	function lost439(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(439,'type',$pa);
	}
	
	function check_unlocked439(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(439,$pd) && check_unlocked439($pd))
		{
			eval(import_module('logger'));
			$var_439=\skillbase\skill_getvalue(439,'type',$pd);
			if ($var_439!=$pa['wep_kind']){
				\skillbase\skill_setvalue(439,'type',$pa['wep_kind'],$pd);
			}else{
				\skillbase\skill_setvalue(439,'type','X',$pd);
				if ($active) 
					$log .= "<span class=\"yellow b\">黄翔把你的攻击方式ban了，造成的伤害降低75%！</span><br>";
				else 
					$log .= "<span class=\"yellow b\">黄翔把敌人的攻击方式ban了，造成的伤害降低75%！</span><br>";
				$r=Array(0.25);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}

//	function apply_total_damage_modifier_invincible(&$pa,&$pd,$active){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if (!\skillbase\skill_query(439,$pd) || !check_unlocked439($pd)) return $chprocess($pa,$pd,$active);
//		if ($pa['dmg_dealt']<=0) return $chprocess($pa,$pd,$active);
//		eval(import_module('logger'));
//		$var_439=\skillbase\skill_getvalue(439,'type',$pd);
//		if ($var_439!=$pa['wep_kind']){
//			\skillbase\skill_setvalue(439,'type',$pa['wep_kind'],$pd);
//		}else{
//			\skillbase\skill_setvalue(439,'type','0',$pd);
//			if ($active) 
//				$log .= "<span class=\"yellow b\">黄翔把你的攻击方式ban了，造成的伤害降低75%！</span><br>";
//			else 
//				$log .= "<span class=\"yellow b\">黄翔把敌人的攻击方式ban了，造成的伤害降低75%！</span><br>";
//			$pa['dmg_dealt']=ceil($pa['dmg_dealt']/4);
//		}
//		$chprocess($pa,$pd,$active);
//	}
}

?>
