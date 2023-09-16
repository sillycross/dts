<?php

namespace skill422
{
	
	function init() 
	{
		define('MOD_SKILL422_INFO','card;unique;hidden;');
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
	
	function check_unlocked422(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_WG_att_as_WP_modifier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(422,$pa))&&(check_unlocked422($pa))){
			return $chprocess($pa,$pd,$active)*12;		//抵消原本的0.1倍，实际是1.2倍
		}
		return $chprocess($pa,$pd,$active);
	}
	
	function get_WJ_att_as_WP_modifier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(422,$pa))&&(check_unlocked422($pa))){
			return $chprocess($pa,$pd,$active)*10;		//抵消原本的0.1倍，实际是1倍
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
	
	function attr_dmg_check_not_WPG(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(422,$pa))&&(check_unlocked422($pa))) return 1;	//枪械当钝器也可以造成属性伤害
		return $chprocess($pa, $pd, $active);
	}
		
}

?>
