<?php

namespace npcchat
{
	function init() {}
	
	function npcchat(&$pa, &$pd, $active, $situation, $print = 1)
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
		
		
		$sid = -1;
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
		elseif ($situation == 'cannot_counter' && $flag)	//NPC反击检定失败
		{
			$sid = 10;
		}
		elseif ($situation == 'out_of_range' && $flag)	//NPC射程不足无法反击
		{
			$sid = 11;
		}
		elseif ($situation == 'kill')
		{
			if ($flag)	
				$sid = 13;	//击杀玩家 原为13，但不需要在这里设置
			else  $sid = 9;	//被击杀
		}
		elseif ($situation == 'critical') //必杀技
		{
			$sid = 12;
		}
		
		if (!isset($nchat[$sid])) return;
		
		if($print){
			$chatcolor = $nchat['color'];
			if(!empty($chatcolor)){
				$npcwords = "<span class = \"{$chatcolor}\">";
			}else{
				$npcwords = '<span>';
			}
			$npcwords.=$nchat[$sid].'</span><br>';
		
			eval(import_module('logger'));
			$log .= $npcwords;
		}
		return $npcwords;
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
		//if ($pd['type'] && $pd['hp']>0) npcchat($pa, $pd, $active, 'battle');
	}
	
	function cannot_counter(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['type']) {
			if(isset($pa['out_of_range'])) npcchat($pa, $pd, $active, 'out_of_range');
			else npcchat($pa, $pd, $active, 'cannot_counter');
		}
		$chprocess($pa, $pd, $active);
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['type'] || $pd['type']) npcchat($pa, $pd, $active, 'kill');
		$chprocess($pa, $pd, $active);
	}
	
	function get_player_killmsg(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pdata);
		eval(import_module('player'));
		if ($pdata['type']>0 && isset($npcchat[$pdata['type']][$pdata['name']])){
			$ret = '';//不需要返回杀人台词
		}
		return $ret;
	}
}

?>
