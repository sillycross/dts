<?php

namespace npcchat
{
	function init() {}
	
	function npcchat(&$pa, &$pd, $active, $situation)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('npcchat'));
		
		if (!$npcchaton) return;
		
		if ($pa['type'])
		{
			if (!isset($npcchat[$pa['type']])) return;
			if (isset($npcchat[$pa['type']][$pa['name']]))
				$nchat = &$npcchat[$pa['type']][$pa['name']];
			else  $nchat = &$npcchat[$pa['type']];
			$flag = 1;
		}
		else
		{
			if (!isset($npcchat[$pd['type']])) return;
			if (isset($npcchat[$pd['type']][$pd['name']]))
				$nchat = &$npcchat[$pd['type']][$pd['name']];
			else  $nchat = &$npcchat[$pd['type']];
			$flag = 0;
		}
		
		$chatcolor = $nchat['color'];
		if(!empty($chatcolor)){
			$npcwords = "<span class = \"{$chatcolor}\">";
		}else{
			$npcwords = '<span>';
		}
		
		if ($situation == 'meet')	//初次登场，会将NPC从睡眠状态变为正常状态
		{
			$sid = 0;
			if($pa['type'] && $pa['state']==1) $pa['state']=0;
			elseif($pd['type'] && $pd['state']==1) $pd['state']=0;
		}
		elseif ($situation == 'battle')
		{
			if ($flag)	//攻击玩家
			{
				if ($pa['hp']>=$pa['mhp']/2)
				{
					if (!$pa['is_counter'])
						$sid = 1;
					else  $sid = 2;
				}
				else  $sid = rand(3,4);
			}
			else		//被玩家攻击
			{
				if ($pd['hp']>=$pd['mhp']/2)
					$sid = rand(5,6);
				else  $sid = rand(7,8);
			}
		}
		else  if ($situation == 'cannot_counter' && $flag)	//NPC无法反击玩家
		{
			$sid = rand(10,11);
		}
		else  if ($situation == 'kill')
		{
			if ($flag)	
				$sid = 13;	//击杀玩家
			else  $sid = 9;	//被击杀
		}
		
		if (!isset($nchat[$sid])) return;
		$npcwords.=$nchat[$sid].'</span><br>';
		
		eval(import_module('logger'));
		$log .= $npcwords;
	}
	
	function battle_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (($pa['type'] && $pa['state']==1) || ($pd['type'] && $pd['state']==1)) npcchat($pa, $pd, $active, 'meet');
		$chprocess($pa, $pd, $active);
	}
	
	function attack_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if ($pa['type']) npcchat($pa, $pd, $active, 'battle');
	}
	
	function attack_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if ($pd['type'] && $pd['hp']>0) npcchat($pa, $pd, $active, 'battle');
	}
	
	function cannot_counter(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if ($pa['type'] || $pd['type']) npcchat($pa, $pd, $active, 'cannot_counter');
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['type'] || $pd['type']) npcchat($pa, $pd, $active, 'kill');
		$chprocess($pa, $pd, $active);
	}
}

?>
