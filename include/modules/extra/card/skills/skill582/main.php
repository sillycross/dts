<?php

namespace skill582
{
	function init() 
	{
		define('MOD_SKILL582_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[582] = '招福';
	}
	
	function acquire582(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(582,'choice','2',$pa);
	}
	
	function lost582(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(582,'choice',$pa);
	}
	
	function check_unlocked582(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade582()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(582) || !check_unlocked582($sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$val = (int)get_var_input('skillpara1');
		if ($val<1 || $val>3)
		{
			$log .= '参数不合法。<br>';
			return;
		}
		\skillbase\skill_setvalue(582,'choice',$val);
		$log.='设置成功。<br>';
	}
	
	function calculate_meetman_rate($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(582) || !check_unlocked582()) return $chprocess($schmode);
		$choice = \skillbase\skill_getvalue(582,'choice');
		if ($choice==1) return 1.2*$chprocess($schmode);
		else if ($choice==3) return 0.8*$chprocess($schmode);
		return $chprocess($schmode);
	}
	
	function discover($schmode){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($schmode);
		eval(import_module('sys','player'));
		if (\skillbase\skill_query(582) && check_unlocked582())
		{
			eval(import_module('logger'));
			$choice = \skillbase\skill_getvalue(582,'choice');
			if ($choice==1)
			{
				$money_lost = min($money, 5);
				$money -= $money_lost;
				if ($money_lost > 0) $log .= "<span class=\"yellow b\">你试图招揽客人，但你发现自己留不住钱了！</span><br>你失去了<span class=\"yellow b\">$money_lost</span>元金钱。<br>";
			}
			else if ($choice==3)
			{
				$money += 5;
				$log .= "你用招财的能力获得了<span class=\"yellow b\">5</span>元金钱！<br>";
			}
		}
		return $ret;
	}
}

?>