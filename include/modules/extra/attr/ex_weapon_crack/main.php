<?php

namespace ex_weapon_crack
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['^wc'] = '碎刃';
		$itemspkdesc['^wc'] = '受到武器攻击时对其造成额外损耗';
		$itemspkremark['^wc'] = '受到武器攻击时，根据具有该属性的装备效果值总和与对方武器，<br>对其造成额外的耐久损耗。';
	}
	
	//受武器攻击时武器损耗增加
	function calculate_wepimp(&$pa, &$pd, $active, $is_hit)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active, $is_hit);
		if (\attrbase\check_in_itmsk('^wc', \attrbase\get_ex_def_array($pa, $pd, $active)) && $is_hit)
		{
			//获得防御方装备中有碎刃属性的武器与防具的效果值总和
			$wcesum = 0;
			//饰品的数值没有意义
			foreach (array('wep','arb','arh','ara','arf') as $pos)
			{
				if (\attrbase\check_in_itmsk('^wc', $pd[$pos.'sk'])) $wcesum += $pd[$pos.'e'];
			}
			if (0 == $pa['wepimp']) $pa['wepimp'] = 1;
			//现在连击属性只有第一次连击会判一次
			if (!isset($pa['wcflag']))
			{
				//首先根据防御方装备中有碎刃属性的武器与防具的效果值总和决定第一个倍率
				$pa['wepimp'] *= round(sqrt($wcesum) / 10) + 1;
				eval(import_module('weapon'));
				//再根据攻击方武器的数值决定第二个倍率
				if ($pa['weps'] == $nosta) $pa['wepimp'] *= round(0.01 * $pa['wepe']) + 1;
				else $pa['wepimp'] *= round(0.01 * $pa['weps']) + 1;
				//再根据攻击方武器是否有连击决定第三个倍率
				if (\attrbase\check_in_itmsk('r', \attrbase\get_ex_attack_array($pa, $pd, $active))) $pa['wepimp'] *= rand(2,4);
				$pa['wcflag'] = 1;
			}
		}
	}
	
	function apply_weapon_imp(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('weapon'));
		if (isset($pa['wepimp']) && $pa['wepimp'])
		{
			if (\attrbase\check_in_itmsk('^wc', \attrbase\get_ex_def_array($pa, $pd, $active)) && isset($pa['wcflag']))
			{
				eval(import_module('logger'));
				if ($pa['wep_kind'] == 'N')
				{
					if ($active) $log .= "<span class=\"yellow b\">{$pd['name']}的装备使你的<span class=\"red b\">腕</span>部受伤了！</span><br>";
					else $log .= "<span class=\"yellow b\">你的装备使{$pa['name']}的<span class=\"red b\">腕</span>部受伤了！</span><br>";
					\wound\get_inf('a', $pa);
				}
				else
				{
					if ($active) $log .= "<span class=\"yellow b\">{$pd['name']}的装备使你的武器损耗增加了！</span><br>";
					else $log .= "<span class=\"yellow b\">你的装备使{$pa['name']}的武器损耗增加了！</span><br>";
					
					//无限耐的情况灵、投等武器也会损耗效果值
					if (!in_array($pa['wep_kind'], array('P','K','G','J','B')) && $pa['weps']==$nosta)
					{
						$pa['wepe'] -= $pa['wepimp'];
						if ($active)
							$log .= "你的{$pa['wep']}的攻击力下降了{$pa['wepimp']}！<br>";
						else  $log .= "{$pa['name']}的{$pa['wep']}的攻击力下降了{$pa['wepimp']}！<br>";						
						if ($pa['wepe']<=0) \weapon\weapon_break($pa, $pd, $active);
					}
				}
			}
		}
		$chprocess($pa, $pd, $active);
	}
	
}

?>
