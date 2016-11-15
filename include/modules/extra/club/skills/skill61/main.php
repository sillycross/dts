<?php

namespace skill61
{
	function init() 
	{
		define('MOD_SKILL61_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[61] = '黑暗';
	}
	
	function acquire61(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost61(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked61(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_sk_procrate61(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if ($pa===NULL) $clb=$club; else $clb=$pa['club'];
		if ($clb==24) return 35; else return 15;
	}
	
	function get_hp_procrate61(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if ($pa===NULL) $clb=$club; else $clb=$pa['club'];
		if ($clb==24) return 35; else return 15;
	}

	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(61,$pa))
		{
			eval(import_module('logger'));
			if (rand(0,99)<get_sk_procrate61($pa))
			{
				eval(import_module('weapon'));
				foreach (array_unique(array_values($skillinfo)) as $key)
					if ($pd[$key]>0)
					{
						$pa[$key]++;
						$pd[$key]--;
					}
				if ($active)
					$log.='<span class="yellow">你吸取了敌人1点全系熟练！</span><br>';
				else  $log.='<span class="yellow">敌人吸取了你1点全系熟练！</span><br>';
			}
			if (rand(0,99)<get_hp_procrate61($pa))
			{
				$pa['mhp']++; $pa['hp']++;
				if ($pd['mhp']>1)
				{
					$pd['mhp']--; $pd['hp']--;
				}
				if ($active)
					$log.='<span class="yellow">你吸取了敌人1点生命上限！</span><br>';
				else  $log.='<span class="yellow">敌人吸取了你1点生命上限！</span><br>';
			}
		}
		$chprocess($pa,$pd,$active);
	}
}

?>
