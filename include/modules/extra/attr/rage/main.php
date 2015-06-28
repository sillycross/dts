<?php

namespace rage
{
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['HR']='怒气增加';
		$itemspkinfo['c']='重击辅助';
	}
	
	//计算攻击经验获得
	function calculate_attack_rage_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$rageup = round ( ($pa['lvl'] - $pd['lvl']) / 3 );
		$rageup = $rageup > 0 ? $rageup : 1;
		return $rageup;
	}
	
	function strike_finish(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['is_hit'])	//被命中才有怒气
		{
			$pd['rage']+=calculate_attack_rage_gain($pa, $pd, $active);
			$pd['rage']=min($pd['rage'],100);
		}
		$chprocess($pa,$pd, $active);
	}
	
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','wound','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'HR' ) === 0) 
		{
			$rageup = min(100-$rage,$itme);
			if ($rageup<=0)
			{
				$log.='你已经出离愤怒了，动怒伤肝，还是歇歇吧！<br>';
				return;
			}
			$rage += $rageup;
			$log.="你吃了一口{$itm}，顿时感觉心中充满了愤怒。你的怒气值增加了<span class=\"yellow\">{$rageup}</span>点！<br>";
			\itemmain\itms_reduce($theitem);
			return;
		}
		
		$chprocess($theitem);
	}
	
	function attack_prepare(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['original_rage']=$pa['rage'];
		$chprocess($pa, $pd, $active);
	}
	
	function attack_finish(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\attrbase\check_itmsk('c',$pa))
		{
			$lost_rage = $pa['original_rage']-$pa['rage'];
			if ($lost_rage > 0)
			{
				$payback_rage = round($lost_rage/100*rand(15,20));
				$pa['rage']+=$payback_rage;
				if ($pa['rage']>100) $pa['rage']=100;
			}
		}
		$chprocess($pa, $pd, $active);
	}
}

?>
