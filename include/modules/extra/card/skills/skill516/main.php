<?php

namespace skill516
{
	function init() 
	{
		define('MOD_SKILL516_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[516] = '分割';
	}
	
	function acquire516(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(516,'lastdmg','0',$pa);
	}
	
	function lost516(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked516(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//特殊变化次序注册
	function apply_total_damage_modifier_special_set_sequence(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(516,$pd) && check_unlocked516($pd)) 
			$pd['atdms_sequence'][300] = 'skill516';//放在最后
		return;
	}
	
	//特殊变化生效判定，建议采用或的逻辑关系
	function apply_total_damage_modifier_special_check(&$pa, &$pd, $active, $akey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $akey);
		if('skill516' == $akey && $pa['dmg_dealt'] > 0) $ret = 1;
		return $ret;
	}
	
	//特殊变化执行
	function apply_total_damage_modifier_special_core(&$pa, &$pd, $active, $akey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd, $active, $akey);
		if('skill516' == $akey && \skillbase\skill_query(516,$pd) && check_unlocked516($pd)){
			//先记录变化前的伤害
			$pd['original_dmg_skill516'] = $pa['dmg_dealt'];
			//然后判定是不是归零
			if($pa['dmg_dealt'] < \skillbase\skill_getvalue(516,'lastdmg',$pd)) {
				$pa['dmg_dealt'] = 0;
				eval(import_module('logger'));
				$log .= \battle\battlelog_parser($pa, $pd, $active, "<span class='yellow b'>然而<:pa_name:>的攻击没能突破<:pd_name:>的伤害之壁！</span><br>");
			}
		}
	}
	
	//结算伤害时记录上一次伤害
	function apply_damage(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(516,$pd) && check_unlocked516($pd))
		{
			$dmg_skill516 = $pa['dmg_dealt'];
			//选项1：每次伤害都记录
			//if(!empty($pd['original_dmg_skill516']) && $dmg_skill516 < $pd['original_dmg_skill516']) $dmg_skill516 = $pd['original_dmg_skill516'];
			//if($dmg_skill516 > 0) \skillbase\skill_setvalue(516,'lastdmg',$dmg_skill516,$pd);
			
			//选项2：被打穿时才记录
			if($dmg_skill516 > \skillbase\skill_getvalue(516,'lastdmg',$pd)) \skillbase\skill_setvalue(516,'lastdmg',$dmg_skill516,$pd);
		}
		return $ret;
	}
	
	//阵亡归零
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(516,$pd) && check_unlocked516($pd))
		{
			\skillbase\skill_setvalue(516,'lastdmg',0,$pd);
		}
		$chprocess($pa, $pd, $active);
	}
}

?>