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
			$pa['itm1'] = '黄久鸡块';
			$pa['itmk1'] = 'HH';
			$pa['itme1'] = 144;
			$pa['itms1'] = 30;
			$pa['itm2'] = '百世可乐';
			$pa['itmk2'] = 'HS';
			$pa['itme2'] = 144;
			$pa['itms2'] = 30;
			$pa['itm3'] = '碎骨肉相连';
			$pa['itmk3'] = 'HB';
			$pa['itme3'] = 100;
			$pa['itms3'] = 10;
			$pa['itmsk3'] = '';
			$pa['itm4'] = '新奥尔良烤乌翅';
			$pa['itmk4'] = 'HB';
			$pa['itme4'] = 100;
			$pa['itms4'] = 10;
			$pa['itmsk4'] = '';
			$pa['itm5'] = '深海皇鱼堡';
			$pa['itmk5'] = 'HB';
			$pa['itme5'] = 100;
			$pa['itms5'] = 10;
			$pa['itmsk5'] = '';
			$pa['itm6'] = '葡式蛋挞';
			$pa['itmk6'] = 'HB';
			$pa['itme6'] = 100;
			$pa['itms6'] = 10;
			$pa['itmsk6'] = '';
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
	
	function itemuse_edible(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($theitem);
		eval(import_module('sys','logger','player'));
		if (\skillbase\skill_query(553,$sdata))
		{
			//还钱
			$debt = \skillbase\skill_getvalue(553,'debt');
			if($money >= $debt && $hp > 0){
				$itm = $theitem['itm'];
				$log .= "你正享用着{$itm}，嘴里突然传来一声提示音：<span class=\"yellow b\">“已支付{$debt}元，欠款已还清，感谢您支持肯东基疯狂星期四活动！”</span><br>";		
				$money -= $debt;
				\skillbase\skill_lost(553);
			}
		}
	}
}

?>