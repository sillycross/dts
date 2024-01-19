<?php

namespace gameflow_antiafk
{
	function init() {}
	
	function reset_game()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		
		$chprocess();
		
		eval(import_module('sys','gameflow_antiafk'));
		//修改反挂机间隔
		$afktime = $starttime;
		//save_gameinfo();//已改到外侧
	}
	
	//判定是否到点触发反挂机
	//反挂机规则：从游戏开始后，每隔规定的时间（10分钟）判定一次，杀死所有10分钟没动的玩家。所以有时玩家存活时间会大于10分钟
	function check_triggered_antiAFK()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		if($gamestate >= 20 && empty($gamevars['forbid_antiAFK'])) {//游戏开始后才判定反挂机。如果$gamevars['forbid_antiAFK']为真，会直接跳过触发
			//标准房是连斗后反挂机，房间内是1禁后反挂机
			if(0==$room_id && \gameflow_combo\is_gamestate_combo() && $now > $afktime + get_antiAFKertime() * 60) 
				return true;
			if($room_id > 0 && \map\get_area_wavenum() > 0 && $now > $afktime + get_antiAFKertime() * 60)
				return true;
		}
		return false;
	}
	
	//根据房间号获得对应的反挂机间隔时间
	function get_antiAFKertime()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','gameflow_antiafk'));
		if($room_id > 0) return $antiAFKertime_room;
		return $antiAFKertime_normal;
	}
	
	function gamestateupdate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','gameflow_antiafk'));
		//长时间没人刷新游戏后，优先反挂机再禁区，所以在$chprocess之前
		if(check_triggered_antiAFK()) antiAFK(get_antiAFKertime());

		$chprocess();
	}
	
	//反挂机主函数
	function antiAFK($timelimit = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','gameflow_antiafk'));
		if(empty($timelimit)){
			$timelimit = get_antiAFKertime();
		}
		$timelimit *= 60;
		$deadline=$now-$timelimit;
		$updatelist = $afkerlist = Array();
		
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE type=0 AND endtime < '$deadline' AND hp>'0' AND state<'10'");
		while($al = $db->fetch_array($result)) {
			$updatelist[]=Array('name' => $al['name'] ,'pid' => $al['pid'], 'hp' => 0, 'state' => 32);
			$afkerlist[]=Array('name' => $al['name'] ,'pls' => $al['pls']);
		}

		if(empty($updatelist)) return;
		
		$antiAFKnum = sizeof($updatelist);
		$db->multi_update("{$tablepre}players", $updatelist, 'pid');//一次性更新player表
		
		foreach($afkerlist as $av){//更新进行状况，因为可能有模块继承了addnews函数，暂时只能在循环体里处理。以后应该改掉
			addnews($now,'death32',$av['name'],'',$av['pls']);
		}
		
		$alivenum -= $antiAFKnum; if($alivenum < 0) $alivenum = 0;
		$deathnum += $antiAFKnum;
		
		$afktime = $now;
		save_gameinfo();
		return;
	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map'));
		if($news == 'death32')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，躲藏于<span class=\"red b\">$plsinfo[$c]</span>的<span class=\"yellow b\">$a</span><span class=\"red b\">挂机时间过长</span>，被在外等待的愤怒的玩家们私刑处死！</li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
