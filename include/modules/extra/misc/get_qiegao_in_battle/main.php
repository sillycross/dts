<?php

namespace get_qiegao_in_battle
{
	function init() 
	{
	}
	
	//计算切糕掉落
	function calc_qiegao_drop(&$pa,&$pd,&$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase','sys','logger','map'));
		$qiegaogain=0;
		if (!in_array($gametype,$qiegao_ignore_mode)){		
			if ($pd['type']==90)	//杂兵
			{
				if ($areanum/$areaadd<1)	//0禁
				{
					$dice = rand(0,99);
					if ($dice<5) 
						$qiegaogain=rand(7,15);
					else if ($dice<20)
						$qiegaogain=rand(3,7);
					else if ($dice<50)
						$qiegaogain=rand(1,3);
				}
				else if ($areanum/$areaadd<2)	//1禁
				{
					$dice = rand(0,99);
					if ($dice<5) 
						$qiegaogain=rand(3,5);
					else if ($dice<15)
						$qiegaogain=rand(1,3);
				}
			}
			if ($pd['type']==2)	//幻象
			{
				if ($areanum/$areaadd<1)
				{
					$qiegaogain=rand(9,19);
				}
				else if ($areanum/$areaadd<2)
				{
					$dice=rand(0,99);
					if ($dice<30)
						$qiegaogain=rand(3,7);
					else  $qiegaogain=rand(1,3);
				}
			}
		}
		return $qiegaogain;
	}
	
	//击杀敌人时获得切糕
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		battle_get_qiegao($pa,$pd,$active);
	}	
	
	function battle_get_qiegao(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$qiegaogain=calc_qiegao_drop($pa,$pd,$active);
		if ($qiegaogain>0){
			battle_get_qiegao_update($qiegaogain,$pa);
			$log.="<span class=\"orange\">敌人掉落了{$qiegaogain}单位的切糕！</span><br>";
		}
		return $qiegaogain;
	}
	
	//更新切糕。如果有1003号技能会存在1003号技能里（写在该模块）
	function battle_get_qiegao_update($qiegaogain,&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\cardbase\get_qiegao($qiegaogain,$pa);
	}
}

?>