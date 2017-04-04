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

	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if($news == 'duel') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">游戏进入死斗阶段！</span><br>\n";
		if($news == 'duelkey') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}使用了{$b}，启动了死斗程序！</span><br>\n";
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
	
	function check_player_discover(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($edata['type']>0 && $gamestate >= 50) return 0;	//死斗后无NPC
		return $chprocess($edata);
	}
	
	function calculate_meetman_rate($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($gamestate >= 50) 
		{
			if ($schmode == 'search') return $chprocess($schmode)*1.1;
			if ($schmode == 'move') return $chprocess($schmode)*1.05;
		}
		return $chprocess($schmode);
	}
}

?>
