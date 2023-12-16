<?php

namespace npcchat
{
	function init() {}
	
	//主函数，在各个地方调用这个函数并给出$situation，函数会选择合适的npc台词并返回。注意这里不会直接显示台词
	function npcchat(&$pa, &$pd, $active, $situation, $print = 1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('npcchat'));
		
		if (!$npcchaton) return;
		if(!$pa['type'] && !$pd['type']) return;
		
		if ($pa['type'])
		{
			if (!isset($npcchat[$pa['type']])) return;
			if (isset($npcchat[$pa['type']][$pa['name']]))
				$nchat = &$npcchat[$pa['type']][$pa['name']];
			else  $nchat = &$npcchat[$pa['type']];
			$npc_active = 1;
		}
		else
		{
			if (!isset($npcchat[$pd['type']])) return;
			if (isset($npcchat[$pd['type']][$pd['name']]))
				$nchat = &$npcchat[$pd['type']][$pd['name']];
			else  $nchat = &$npcchat[$pd['type']];
			$npc_active = 0;
		}
		
		list($chattag, $sid) = npcchat_tag_process($pa, $pd, $active, $situation, $npc_active, $nchat);
		
		$chatlog = npcchat_get_chatlog($chattag,$sid,$nchat);
		
		if(NULL===$chatlog) return;
		
		if($print){
			npcchat_print(npcchat_decorate($chatlog, $nchat, $chattag));
		}
		return $chatlog;
	}
	
	//显示NPC台词
	function npcchat_print($printlog)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$log .= $printlog;
	}
	
	//给NPC台词外面加span标签并赋予对应的class
	function npcchat_decorate($chatlog, $nchat, $chattag)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!empty($nchat['color'])){
			$printlog = "<span class = \"{$nchat['color']}\">".$chatlog;
		}else{
			$printlog = '<span>'.$chatlog;
		}
		$printlog .= '</span><br>';
		if('kill' == $chattag || 'retreat' == $chattag) $printlog .= '<br>';
		return $printlog;
	}
	
	//从台词资源中提取对应的台词，兼容新旧两种格式（旧是数字下标，新有键名）
	function npcchat_get_chatlog($chattag,$sid,$nchat){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chatlog = NULL;
		if(isset($nchat[$chattag])) {
			$chatlog = $nchat[$chattag];
			if(is_array($chatlog)){
				$chatlog = array_randompick($chatlog);
			}
		}elseif(isset($nchat[$sid])){
			$chatlog = $nchat[$sid];
		}
		return $chatlog;
	}
	
	//npcchat的核心函数，根据$situation和双方数据，给出新旧两种格式的台词编号
	function npcchat_tag_process(&$pa, &$pd, $active, $situation, $npc_active, $nchat){
		if (eval(__MAGIC__)) return $___RET_VALUE;
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
			if ($npc_active)	//攻击玩家
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
		elseif ($situation == 'cannot_counter' && $npc_active)	//NPC反击检定失败
		{
			$sid = 10;
			$chattag = 'cannot_counter';
		}
		elseif ($situation == 'out_of_range' && $npc_active)	//NPC射程不足无法反击
		{
			$sid = 11;
			$chattag = 'out_of_range';
		}
		elseif ($situation == 'kill')
		{
			if ($npc_active){
				$sid = 13;	//击杀玩家 原为13，但不需要在这里设置
				$chattag = 'kill';
				$pa['npcchat_kill'] = 1;
			}else {
				$sid = 9;	//被击杀
				if($pd['hp'] > 0 && !empty($nchat['revive'])) $chattag = 'revive';
				else $chattag = 'retreat';
			}
		}
		elseif ($situation == 'critical') //必杀技
		{
			$sid = 12;
			$chattag = 'critical';
		}
		elseif ($situation == 'addnpc') //addnpc入场特殊宣言
		{
			$sid = 15;
			$chattag = 'addnpc';
		}
		return array($chattag, $sid);
	}
	
	function battle_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['type'] && $pa['state']==1) npcchat($pa, $pd, $active, 'meet');//改为只有NPC主动出击才会喊见面台词
		//if (($pa['type'] && $pa['state']==1) || ($pd['type'] && $pd['state']==1)) npcchat($pa, $pd, $active, 'meet');
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
		if(!empty($pd['npc_evolved'])) $npcchat_pdflag = 0;
		//复活了别说话
		if(!empty($pd['npc_revived'])) $npcchat_pdflag = 0;
		//没打中别说话，否则太话痨了
		if(empty($pa['dmg_dealt']) && $pa['dmg_dealt'] <= 0) $npcchat_pdflag = 0;
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
		$chprocess($pa, $pd, $active);
		if ($pa['type'] || $pd['type']) npcchat($pa, $pd, $active, 'kill');
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
	
	//秒杀阶段如果成功秒杀，发布必杀技宣言
	function apply_total_damage_modifier_seckill(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if (($pa['type'] && !empty($pa['seckill'])) || ($pd['type'] && !empty($pd['seckill']))) 
		{
			npcchat($pa, $pd, $active, 'critical');
		}
	}
}

?>