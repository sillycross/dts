<?php

namespace skill64
{
	function init() 
	{
		define('MOD_SKILL64_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[64] = '索魂';
	}
	
	function acquire64(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost64(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function check_unlocked64(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=15;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if (\skillbase\skill_query(64,$pa) && check_unlocked64($pa))
		{
			eval(import_module('weapon'));
			if ($pa['bskill']==60)
				$arr=Array(0,1,2,3);
			else  $arr=Array(rand(0,3));
			for ($i=0; $i<=3; $i++)
				if (in_array($i,$arr))
				{
					$dice = rand(3,5);
					if ($i==0)
					{
						//命体上限
						$pa['mhp']+=$dice; $pa['msp']+=$dice;
					}
					if ($i==1)
					{
						//经验值
						\lvlctl\getexp($dice,$pa);
					}
					if ($i==2)
					{
						//全系熟练
						foreach (array_unique(array_values($skillinfo)) as $key)
							$pa[$key]+=$dice;
					}
					if ($i==3)
					{
						//基础攻防
						$pa['att']+=$dice; $pa['def']+=$dice;
					}
				}	
		}
		$chprocess($pa,$pd,$active);
	}
}

?>
