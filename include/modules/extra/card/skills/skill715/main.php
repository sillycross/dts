<?php

namespace skill715
{
	$skill715_act_time = 600;
	
	function init() 
	{
		define('MOD_SKILL715_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[715] = '封印';
	}
	
	function acquire715(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill715'));
		\skillbase\skill_setvalue(715, 'expire', $now + $skill715_act_time, $pa);
	}
	
	function lost715(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked715(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function check_available715(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!$pa)
		{
			eval(import_module('player'));
			$pa = $sdata;
		}
		eval(import_module('sys','skill715'));
		if(!\skillbase\skill_query(715,$pa)) return false;
		$expire = \skillbase\skill_getvalue(715,'expire',$pa);
		if($now > $expire) return false;
		else return true;
	}
	
	//被封印时无法行动；解除封印后的第一次行动获得技能
	function pre_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(715) && check_available715())
		{
			eval(import_module('sys','logger'));
			$log .= "<span class=\"yellow b\">你现在处于被封印状态，什么都做不了！</span><br>";
			$mode = 'command'; $command = 'menu';
		}
		elseif (\skillbase\skill_query(715) && !check_available715() && !\skillbase\skill_getvalue(715,'flag1'))
		{
			eval(import_module('logger'));
			$log .= "<span class=\"yellow b\">一位路过的好心人帮你解除了封印！</span><br>你感到力量充满了全身！<br>你获得了技能「灵力」「圣光」「金刚」「神速」。<br>";
			\skillbase\skill_acquire(65);
			\skillbase\skill_acquire(25);
			\skillbase\skill_acquire(44);
			\skillbase\skill_acquire(41);
			\skillbase\skill_setvalue(41,'u','1');
			\skillbase\skill_setvalue(715,'flag1','1');
		}
		$chprocess();
	}
	
	//被攻击时解除封印
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (($pa['is_hit']) && \skillbase\skill_query(715, $pd) && check_available715($pd))
		{
			eval(import_module('logger'));
			if ($active)
				$log .= "<span class=\"lime b\">你打破了{$pd['name']}的封印！</span><br>";
			else $log .= "<span class=\"lime b\">你的封印被{$pa['name']}打破了！</span><br>";
			\skillbase\skill_setvalue(715,'expire','0',$pd);
		}
		$chprocess($pa, $pd, $active);
	}
	
	//被封印时不会反击
	function check_can_counter(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(715, $pa) && !\skillbase\skill_getvalue(715, 'flag1', $pa)) return 0;
		return $chprocess($pa, $pd, $active);
	}
	
	//解除封印后发现特定道具
	function discover_item()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(715) && !check_available715() && !\skillbase\skill_getvalue(715,'flag2'))
		{
			$itm = array(
				'iid' => 0,
				'itm' => '『感情的摩托车』',
				'itmk' => 'DFS',
				'itme' => '464',
				'itms' => '900',
				'itmsk' => 'mR^hu464',
			);
			$itm0 = $itmk0 = $itmsk0 = '';
			$itme0 = $itms0 = 0;
			
			eval(import_module('logger'));
			$log .= "<span class=\"yellow b\">你找回了自己心爱的坐骑。</span><br>";
			\skillbase\skill_setvalue(715,'flag2','1');
			\itemmain\focus_item($itm);
			return true;
		}
		return $chprocess();
	}
	
}

?>