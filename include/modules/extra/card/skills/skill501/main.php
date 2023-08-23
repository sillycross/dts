<?php

namespace skill501
{
	function init() 
	{
		define('MOD_SKILL501_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[501] = '无尽';
	}
	
	function acquire501(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost501(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked501(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function post_winnercheck_events($wn){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(2 == $winmode) {
			//独存=死亡。团队幸存不是这个winmode
			$pa = \player\fetch_playerdata($wn);
			if(\skillbase\skill_query(501,$pa)) {
				$pa['state'] = 42;
				$pa['sourceless'] = 1;
				\player\kill($pa,$pa);
				\player\player_save($pa);
				$winmode = 1;
				$winnum = 0;
				$winner = '';
			}
		}
		$chprocess($wn);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map'));
		if($news == 'death42')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">不愿意独存的<span class=\"yellow b\">$a</span>绝望地自尽了</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>