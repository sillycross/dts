<?php

namespace skill422
{
	
	function init() 
	{
		define('MOD_SKILL422_INFO','club;unique;locked;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[422] = '枪斗';
	}
	
	function acquire422(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost422(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
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
	
	function check_unlocked422(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function attack_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		if ((\skillbase\skill_query(422,$pa))&&(check_unlocked422($pa))){
			$pa['is_wpg']=false;
		}
		if ((\skillbase\skill_query(422,$pd))&&(check_unlocked422($pd))){
			$pd['is_wpg']=false;
		}
	}
	
	function get_WG_att_as_WP(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(422,$pa))&&(check_unlocked422($pa))){
			return $chprocess($pa,$pd,$active)*12;//实际是1.2倍
		}
		return $chprocess($pa,$pd,$active);
	}
	
	function get_WJ_att_as_WP(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(422,$pa))&&(check_unlocked422($pa))){
			return $chprocess($pa,$pd,$active)*10;
		}
		return $chprocess($pa,$pd,$active);
	}
	
	function calculate_wepimp_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		$r=1;
		if ((\skillbase\skill_query(422,$pa))&&(check_unlocked422($pa))){//抵消额外的武器损伤
			if ($pa['wep_kind']=='P' && substr($pa['wepk'],1,1) == 'J') $r=0.15;
			if ($pa['wep_kind']=='P' && substr($pa['wepk'],1,1) == 'G') $r=0.4;
		}
		return $chprocess($pa, $pd, $active)*$r;
	}
}

?>
