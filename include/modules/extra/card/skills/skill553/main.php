<?php

namespace skill553
{
	function init() 
	{
		define('MOD_SKILL553_INFO','card;hidden;');
	}
	
	function acquire553(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));
		if (4 == $wday) {
			$pa['itm1'] = '原味炸鸡';
			$pa['itmk1'] = 'HH';
			$pa['itme1'] = 200;
			$pa['itms1'] = 30;
			$pa['itm2'] = '可乐';
			$pa['itmk2'] = 'HS';
			$pa['itme2'] = 200;
			$pa['itms2'] = 30;
			$pa['itm6'] = '蛋挞';
			$pa['itmk6'] = 'HB';
			$pa['itme6'] = 120;
			$pa['itms6'] = 10;
			if ($pa['money'] >= 50) {
				$pa['money'] -= 50;
				\skillbase\skill_lost(553,$pa);
			}
			else {
				\skillbase\skill_setvalue(553,'debt',50 - $pa['money'],$pa);
				$pa['money'] = 0;
			}
		}
		else \skillbase\skill_lost(553,$pa);
	}
	
	function lost553(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function itemuse_edible($item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($item);
		eval(import_module('sys','logger','player'));
		$debt = \skillbase\skill_getvalue(553,'debt');
		if (\skillbase\skill_query(553,$sdata) && $money >= $debt)
		{
			//还钱
			$log .= "你还清了借来的{$debt}元。<br>";		
			$money -= $debt;
			\skillbase\skill_lost(553);
		}
	}
}

?>