<?php

namespace skill206
{
	function init() 
	{
		define('MOD_SKILL206_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[206] = '爆头';
	}
	
	function acquire206(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
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
		return (get_gunner_skillpoint_need($pa)<=0);
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
	
	function apply_damage(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(206,$pa) || !check_unlocked206($pa)) $chprocess($pa,$pd,$active);
		if ((($pd['hp']*0.8)<$pa['dmg_dealt'])&&($pd['hp']>$pa['dmg_dealt'])&&($pa['wep_kind']=='G')){
			$pa['dmg_dealt']=$pd['hp'];
			eval(import_module('logger'));
			if ($active) $log .= "<span class=\"red\">你的攻击直接将敌人爆头！</span><br>";
			else $log .= "<span class=\"red\">敌人的攻击直接将你爆头！</span><br>";
		}
		$chprocess($pa,$pd,$active);
	}
	
}

?>
