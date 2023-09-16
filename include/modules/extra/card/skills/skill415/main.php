<?php

namespace skill415
{
	function init() 
	{
		define('MOD_SKILL415_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[415] = '影像';
	}
	
	function acquire415(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(415,'lvl','0',$pa);
	}
	
	function lost415(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(415,'lvl',$pa);
	}
	
	function check_unlocked415(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//特殊变化次序注册
	function apply_total_damage_modifier_special_set_sequence(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(415,$pd) && check_unlocked415($pd)) 
			$pd['atdms_sequence'][100] = 'skill415';
		return;
	}
	
	//特殊变化生效判定，建议采用或的逻辑关系
	function apply_total_damage_modifier_special_check(&$pa, &$pd, $active, $akey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $akey);
		if('skill415' == $akey) $ret = 1;
		return $ret;
	}
	
	//特殊变化执行
	function apply_total_damage_modifier_special_core(&$pa, &$pd, $active, $akey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd, $active, $akey);
		if('skill415' == $akey) {
			eval(import_module('logger'));
			$var_415=\skillbase\skill_getvalue(415,'lvl',$pd);
			$pa['dmg_dealt']=(int)$pa['dmg_dealt'];
			if ( $var_415==0 && $pa['dmg_dealt']%2 ==0 ){
				$tz=1;
				while ($pa['dmg_dealt']>0 && ($pa['dmg_dealt']%2)==0) 
				{
					$pa['dmg_dealt']=floor($pa['dmg_dealt']/2);	
					$tz*=2;
				}
				if ($tz>1)
				{
					if ($active) 
						$log .= "<span class=\"yellow b\">你的攻击被敌人的技能「影像」防御了，伤害降低至{$tz}分之1！</span><br>";
					else 
						$log .= "<span class=\"yellow b\">敌人的攻击被你的技能「影像」防御了，伤害降低至{$tz}分之1！</span><br>";
				}
			}
			if ( $var_415==1 && $pa['dmg_dealt']%1000 !=666 ){
				$pa['dmg_dealt']=0;
				if ($active) $log .= "<span class=\"yellow b\">敌人的技能「影像」使你的攻击没有造成任何效果！</span><br>";
				else $log .= "<span class=\"yellow b\">你的技能「影像」使敌人的攻击没有造成任何效果！</span><br>";
			}
		}
	}
}

?>