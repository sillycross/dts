<?php

namespace skill578
{
	function init() 
	{
		define('MOD_SKILL578_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[578] = '祥瑞';
	}
	
	function acquire578(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost578(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function check_unlocked578(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function strike(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		if (\skillbase\skill_query(578,$pd) && check_unlocked578($pd))
		{
			eval(import_module('logger','player'));
			$pd['temp_log'] = $log;
		}
		$chprocess($pa, $pd, $active);
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','logger','player'));
		$chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(578,$pd) && check_unlocked578($pd))
		{
			$log = $pd['temp_log'];
			$dice = rand(0,1);
			if ($dice == 0) $log .= "但是没有击中！";
			else
			{
				$ex_att_array = \attrbase\get_ex_attack_array($pa, $pd, $active);
				eval(import_module('ex_dmg_att'));
				$flag = 0;
				$exnum = 0;
				foreach ($ex_attack_list as $key)
				{
					if (in_array($key,$ex_att_array))
					{
						$flag = 1;
						$exnum += 1;
					}
				}
				if ($active)
				{
					$log .= "<span class=\"yellow b\">你的攻击完全被{$pd['name']}的装备吸收了！</span><br>造成了<span class=\"red b\">1</span>点物理伤害！<br>";
				}
				else
				{
					$log .= "<span class=\"yellow b\">{$pa['name']}的攻击完全被你的装备吸收了！</span><br>造成了<span class=\"red b\">1</span>点物理伤害！<br>";
				}
				if ($flag == 0)
				{
					
					$log .= "<span class=\"yellow b\">造成的总伤害：<span class=\"red b\">1</span>。</span><br>";
				}
				else
				{
					$log .= "<span class=\"yellow b\">属性攻击的力量完全被防具吸收了！</span>只造成了<span class=\"red b\">{$exnum}</span>点伤害！</span><br>";
					$fakedmg = $exnum + 1;
					$log .= "<span class=\"yellow b\">造成的总伤害：1 + {$exnum} = <span class=\"red b\">{$fakedmg}</span>。</span><br>";
				}
			}
		}
	}
	
}

?>
