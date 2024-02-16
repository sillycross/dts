<?php

namespace skill568
{
	$skill568_arh = '战斗力指示器';

	function init() 
	{
		define('MOD_SKILL568_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[568] = '锐评';
	}
	
	function acquire568(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost568(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function findenemy(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','skill568'));
		if (\skillbase\skill_query(568) && (strpos($sdata['arh'], $skill568_arh) !== false))
		{
			eval(import_module('logger','metman'));
			$log .= skill568_tips($edata);
		}
		$chprocess($edata);
	}
	
	function attack_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		eval(import_module('skill568'));
		if (\skillbase\skill_query(568) && (strpos($pd['arh'],$skill568_arh) !== false))
		{
			if (!$active && !$pd['is_counter'])
			{
				eval(import_module('logger'));
				$log .= skill568_tips($pa, 1);
			}
		}	
	}
	
	function skill568_tips($edata, $mode = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon','armor','logger'));
		
		$skill_count = count(\skillbase\get_acquired_skill_array($edata));
		$attrs = \itemmain\get_itmsk_array($edata['wepsk']);
		foreach (array('arb','arh','ara','arf','art') as $pos) $attrs = array_merge($attrs, \itemmain\get_itmsk_array($edata[$pos.'sk']));
		$attr_count = count(array_unique($attrs));
		$att = $edata['att'] + $edata['wepe'];
		$def = $edata['def'];
		foreach($armor_equip_list as $pos) $def += $edata[$pos.'e'];
		
		$bp = round((0.8*$att+0.24*$def+0.001*$edata['hp']
		+0.3*($edata['wp']+$edata['wk']+$edata['wc']+$edata['wg']+$edata['wd']+$edata['wf'])
		+140*$skill_count+20*$attr_count-274)
		*(1-0.1*strlen($edata['inf']))*(1+0.01*$edata['rage']*$skill_count));
		
		if (0 === $mode)
		{
			$tips = "一个战斗力为";
			if ($bp >= 5000) $tips .= "<span class=\"red b\">";
			else $tips .= "<span class=\"yellow b\">";
			$tips .= "{$bp}</span>点的身影进入了你的视野！<br>";
		}
		else
		{
			if ($bp <= 10) $tips = "不过是个战斗力<span class=\"yellow b\">{$bp}</span>的渣滓罢了。<br>";
			else if ($bp >= 5000) $tips = "对方的战斗力竟高达<span class=\"red b\">{$bp}</span>点！<br>";
			else $tips = "对方的战斗力为<span class=\"yellow b\">{$bp}</span>点。<br>";
		}
		return $tips;
	}
	
}

?>
