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
		$chattag = '';
		if ($situation == 'meet')	//初次登场，会将NPC从睡眠状态变为正常状态
		{
			$sid = 0;
			$chattag = 'meet';
			if($pa['type'] && $pa['state']==1) $pa['state']=0;
			elseif($pd['type'] && $pd['state']==1) $pd['state']=0;
		}
		elseif ($situation == 'battle')
		{
			if ($flag)	//攻击玩家
			{
				if ($pa['hp']>=$pa['mhp']/2)
				{
					if (!$pa['is_counter']){
						$sid = 1;
						$chattag = 'attackfine';
					}else {
						$sid = 2;
						$chattag = 'counterfine';
					}
				}
				else {
					$sid = rand(3,4);
					if (!$pa['is_counter']){
						$chattag = 'attackhurt';
					}else {
						$chattag = 'counterhurt';
					}
				}
			}
			else		//被玩家攻击
			{
				if ($pd['hp']>=$pd['mhp']/2){
					$sid = rand(5,6);
					$chattag = 'defendfine';
				}else {
					$sid = rand(7,8);
					$chattag = 'defendhurt';
				}
			}
		}
		elseif ($situation == 'cannot_counter' && $flag)	//NPC反击检定失败
		{
			$sid = 10;
			$chattag = 'cannot_counter';
		}
		elseif ($situation == 'out_of_range' && $flag)	//NPC射程不足无法反击
		{
			$sid = 11;
			$chattag = 'out_of_range';
		}
		elseif ($situation == 'kill')
		{
			if ($flag){
				$sid = 13;	//击杀玩家 原为13，但不需要在这里设置
				$chattag = 'kill';
				$pa['npcchat_kill'] = 1;
			}else {
				$sid = 9;	//被击杀
				$chattag = 'retreat';
			}
		}
		elseif ($situation == 'critical') //必杀技
		{
			$sid = 12;
			$chattag = 'critical';
		}
		if(isset($nchat[$chattag])) {
			$chatlog = $nchat[$chattag];
			if(is_array($chatlog)){
				shuffle($chatlog);
				$chatlog = $chatlog[0];
			}
		}elseif(isset($nchat[$sid])){
			$chatlog = $nchat[$sid];
		}else{
			return;
		}
		
		$pringlog = '';
		if($print){
			$chatcolor = $nchat['color'];
			if(!empty($chatcolor)){
				$pringlog = "<span class = \"{$chatcolor}\">".$chatlog;
			}else{
				$pringlog = '<span>'.$chatlog;
			}
			$pringlog .= '</span><br>';
		
			eval(import_module('logger'));
			$log .= $pringlog;
		}
		return $chatlog;
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
		$npcchat_pdflag = 1;
		//进化了别说话
		if(isset($pd['npc_evolved']) && $pd['npc_evolved']) $npcchat_pdflag = 0;
		//没打中别说话，否则太话痨了
		if($pa['dmg_dealt'] <= 0) $npcchat_pdflag = 0;
		if ($pd['type'] && $pd['hp']>0 && $npcchat_pdflag) npcchat($pa, $pd, $active, 'battle');
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
