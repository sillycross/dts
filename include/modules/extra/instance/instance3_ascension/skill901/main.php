<?php

namespace skill901
{
	function init() 
	{
		define('MOD_SKILL901_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[901] = '神弃';
	}
	
	function acquire901(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(901,'lvl','0',$pa);
	}
	
	function lost901(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked901(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_lvllimit(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = $chprocess($pa);
		if (\skillbase\skill_query(901, $pa) && ((int)\skillbase\skill_getvalue(901, 'lvl', $pa) >= 1)) return max($r-100,0);
		else return $r;
	}
	
	function get_shopiteminfo($item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($item);
		if (\skillbase\skill_query(901) && ((int)\skillbase\skill_getvalue(901, 'lvl') >= 2)) $ret['price']=round($ret['price']*1.25);
		return $ret;
	}
	
	function prepare_shopitem($sn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($sn);
		if (\skillbase\skill_query(901) && ((int)\skillbase\skill_getvalue(901, 'lvl') >= 2)) 
		{
			for ($i=0; $i<count($ret); $i++)
				$ret[$i]['price']=round($ret[$i]['price']*1.25);
		}
		return $ret;
	}
	
	function post_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();	
		if (\skillbase\skill_query(901) && ((int)\skillbase\skill_getvalue(901, 'lvl') >= 3))
		{
			if (NULL === \skillbase\skill_getvalue(901,'debt')) \skillbase\skill_setvalue(901,'debt','1000');
			$debt = \skillbase\skill_getvalue(901,'debt');
			eval(import_module('player'));
			if (($debt > 0) && ($money > 0) && ($hp > 0))
			{
				$money_lost = min($debt, $money);
				$debt -= $money_lost;
				$money -= $money_lost;
				\skillbase\skill_setvalue(901,'debt', $debt);
				eval(import_module('logger'));
				$log .= "<span class=\"yellow b\">你的{$money_lost}元化作光点消散了。</span><br>";
				if ($debt <= 0) $log .= "<span class=\"yellow b\">你感到身上破财的运势散尽了。</span><br>";
			}
		}
	}
	
	function calculate_attack_weapon_skill_gain_base(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(901, $pa) && ((int)\skillbase\skill_getvalue(901, 'lvl', $pa) >= 4))
		{
			eval(import_module('sys'));
			if ($now - $starttime <= 300) return 0;
		}
		return $ret;
	}
	
	function use_armor(&$theitem, $pos = '')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		if(((!$pos) && (strpos($itmk, 'DB') === 0)) || ($pos == 'arb'))
		{
			if (\skillbase\skill_query(901) && ((int)\skillbase\skill_getvalue(901, 'lvl') >= 5)) 
			{
				eval(import_module('logger'));
				$log .= "你使用了{$itm}，但是你身上的诅咒将它弹开了。<br>";
				return;
			}
		}
		$chprocess($theitem, $pos);
	}
	
}

?>