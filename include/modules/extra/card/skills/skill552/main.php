<?php

namespace skill552
{
	$skill552_cost = 80;
	$skill552_wep = '伐木斧';
	$skill552_gain_rate = 0.3;

	function init() 
	{
		define('MOD_SKILL552_INFO','card;active;');
		eval(import_module('clubbase'));
		$clubskillname[552] = '伐木';	
	}
	
	function acquire552(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost552(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked552(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=12;
	}

	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		eval(import_module('sys','player','logger','input'));
		if ($mode == 'special' && $command == 'skill552_special' && $subcmd=='castsk552') 
		{
			cast_skill552();
			return;
		}
		$chprocess();
	}

	function cast_skill552()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','input','weapon','skill552'));
		if (!\skillbase\skill_query(552)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		if (\skillbase\skill_getvalue(552,'activated')) 
		{
			$log .= '你已经打造过一把工具了……你要抛弃你的老伙计吗？<br>';
			return;
		}
		if (! $weps || ! $wepe || strpos($wepk,'W')!==0) {
			$log .= '请先装备武器。<br>';
			return;
		}	
		if (strpos($wepsk,'j')!==false)
		{
			$log .= '多重武器不能改造。<br>';
			return;
		}
		if ($mhp <= 80)
		{
			$log .= '你手中的'.$wep.'突然对你喊道：“你连'.$skill552_cost.'点最大生命值都没有，就不要铸我了，还是去铸币吧！”<br>';
			return;
		}
		$mhp -= $skill552_cost;
		if ($mhp <= 0) $mhp = 1;
		if ($hp > $mhp) $hp = $mhp;
		$log .= "你将{$wep}改造成了一把趁手的伐木工具！<br>";		
		// 如果武器有一发属性，耐久变为1
		if (\itemmain\check_in_itmsk('o', $wepsk)) {
			$weps = 1;
			$log .= "但是它好像不怎么耐用的样子……<br>";
		}
		// 如果武器耐久为无限，且为灵/投/射，耐久变为效果的一半
		elseif ($nosta === $weps && in_array(substr($wepk,1,1), array('F','C','B','G'))) $weps = round($wepe / 2);	
		$wepk = 'WK';
		$wep = $skill552_wep;
		\skillbase\skill_setvalue(552,'activated',1);	
		$mode = 'command';
		return;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger','weapon','skill552'));
		if ((\skillbase\skill_query(552,$pa)) && (check_unlocked552($pa)) && (strpos($pa['wep'],$skill552_wep)!== false))
		{
			$gain = round($pa[$skillinfo[substr($pa['wepk'],1,1)]] * $skill552_gain_rate);
			if ($active)
				$log.="<span class=\"yellow b\">「伐木」技能使你额外获得了{$gain}元！<br></span>";
			else $log.="<span class=\"yellow b\">「伐木」技能使敌人额外获得了{$gain}元！<br></span>";
			$pa['money'] += $gain;
		}
		$chprocess($pa, $pd, $active);
	}
}

?>
