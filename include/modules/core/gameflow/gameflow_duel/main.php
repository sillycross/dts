<?php

namespace gameflow_duel
{
	function init() {}
	
	function duel($time = 0,$keyitm = '')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		if($gamestate < 30){
			return 30;
		} elseif($gamestate >= 50) {
			return 51;
		} else{
			$time = $time == 0 ? $now : $time;
			$gamestate = 50;
			save_gameinfo();
			addnews($time,'duelkey',$name,$keyitm);
			addnews($time,'duel');
			systemputchat($time,'duel');
			return 50;
		}	
	}
	
	//判定当前是否已经死斗。有大量没有使用这个函数的判定，遇到再慢慢改吧
	//$disp=1则表明只是用于显示
	function is_gamestate_duel($disp = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		return $gamestate >= 50;
	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if($news == 'duel') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">游戏进入死斗阶段！</span></li>";
		if($news == 'duelkey') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}使用了{$b}，启动了死斗程序！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
//	function check_player_discover(&$edata)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys'));
//		if ($edata['type']>0 && $gamestate >= 50) return 0;	//死斗后无NPC
//		return $chprocess($edata);
//	}

	//死斗后直接摸不到NPC
	function discover_player_filter_alive(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($edata);
		if ($edata['type']>0 && is_gamestate_duel()) 
			$ret = false;	
		return $ret;
	}
	
	function calculate_meetman_rate($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(is_gamestate_duel()) //死斗以后遇敌率上升
		{
			if ($schmode == 'search') return $chprocess($schmode) + 10;//*1.1;
			if ($schmode == 'move') return $chprocess($schmode) + 5;//*1.05;
		}
		return $chprocess($schmode);
	}
}

?>
