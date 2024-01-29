<?php

namespace skill728
{
	function init()
	{
		define('MOD_SKILL728_INFO','card;active;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[728] = '魔盒';
	}
	
	function acquire728(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost728(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked728(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function cast_skill728()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(728,$sdata) || !check_unlocked728($sdata)) 
		{
			$log.='你没有这个技能。';
			return;
		}
		$skill728_cost = get_var_input('skill728_cost');
		if (!empty($skill728_cost))
		{
			$moneycost = (int)$skill728_cost;
			if ($moneycost <= 0)
			{
				$log .= "盒子里传来话音：<span class=\"yellow b\">“喵，你已经没有钱了喵。”</span><br>";
			}
			elseif ($moneycost > $money)
			{
				$log .= "盒子里传来话音：<span class=\"yellow b\">“喵，你没有那么多钱，再确认一下喵。”</span><br>";
			}
			else
			{
				if (rand(0,99) < 50)
				{
					$money += $moneycost;
					if ($moneycost < 100) $log .= "盒子里掉出来数个硬币。你数了数，发现正好是你之前投入数量的两倍。<br>";
					elseif ($moneycost < 3000) $log .= "盒子里掉出来几张纸币。你点了点，发现正好是你之前投入数量的两倍。<br>";
					else $log .= "盒子里涌出了大量的金钱。哇，这下发财了！<br>";
					$log .= "你获得了<span class=\"yellow b\">$moneycost</span>元。<br>";					
				}
				else
				{
					$money -= $moneycost;
					$log .= "盒子晃动了两下，然后静止了下来。盒子里什么也没有出现，投入的金钱也消失了。<br>";
					$log .= "你失去了<span class=\"yellow b\">$moneycost</span>元。<br>";
					if ($moneycost > 3000) $log .= "你似乎听到盒子里传出*嘲笑的猫叫声*……是错觉吗？<br>";
					if ($money == 0) $log .= "<span class=\"red b\">这下破产了……</span><br>";
				}
				$mode = 'command';
				return;
			}
		}
		include template(MOD_SKILL728_CASTSK728);
		$cmd=ob_get_contents();
		ob_end_clean();
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		if ($mode == 'special' && $command == 'skill728_special' && get_var_input('subcmd')=='castsk728') 
		{
			cast_skill728();
			return;
		}
		$chprocess();
	}
	
}

?>