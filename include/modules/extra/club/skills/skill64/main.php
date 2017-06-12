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
			eval(import_module('weapon','logger'));
			if ($pa['bskill']==60)
				$arr=Array(0,1,2,3);
			else  $arr=Array(rand(0,3));
			$tmp_log = array();
			for ($i=0; $i<=3; $i++){
				if (in_array($i,$arr))
				{
					$dice = rand(3,5);
					if ($i==0)
					{
						//命体上限
						$pa['mhp']+=$dice; $pa['msp']+=$dice;
						$tmp_log[] = "<span class='yellow'>最大生命和最大体力</span>增加了{$dice}点";
					}
					if ($i==1)
					{
						//经验值
						\lvlctl\getexp($dice,$pa);
						$tmp_log[] = "<span class='yellow'>经验值</span>增加了{$dice}点";
					}
					if ($i==2)
					{
						//全系熟练
						foreach (array_unique(array_values($skillinfo)) as $key)
							$pa[$key]+=$dice;
						$tmp_log[] = "<span class='yellow'>全系熟练度</span>增加了{$dice}点";
					}
					if ($i==3)
					{
						//基础攻防
						$pa['att']+=$dice; $pa['def']+=$dice;
						$tmp_log[] = "<span class='yellow'>攻击力与防御力</span>增加了{$dice}点";
					}
				}	
			}
			if(!empty($tmp_log)){
				$log .= "你的".implode('，',$tmp_log)."！<br>";
			}
		}
		$chprocess($pa,$pd,$active);
	}
}

?>
