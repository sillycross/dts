<?php

namespace skill486
{	
	$skill486prob = array(0, 50, 100);
	
	function init() 
	{
		define('MOD_SKILL486_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[486] = '无垠';
	}
	
	function acquire486(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(486,'lvl','2',$pa);
		\skillbase\skill_setvalue(486,'rtime','0',$pa);
	}
	
	function lost486(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(486,'lvl',$pa);
		\skillbase\skill_delvalue(486,'rtime',$pa);
	}
	
	function check_unlocked486(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_skill486prob(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!\skillbase\skill_query(486, $pa)) return 0;
		eval(import_module('skill486'));
		$lvl = \skillbase\skill_getvalue(486,'lvl',$pa);
		$rtime = \skillbase\skill_getvalue(486,'rtime',$pa);
		return round($skill486prob[$lvl] * pow(0.5, $rtime));
	}
	
	//复活判定注册
	function set_revive_sequence(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd);
		if(\skillbase\skill_query(486, $pd) && check_unlocked486($pd)){
			$pd['revive_sequence'][150] = 'skill486';
		}
		return;
	}	
	
	//复活判定
	function revive_check(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $rkey);
		if('skill486' == $rkey){
			if(in_array($pd['state'],Array(20,21,22,23,24,25,29,39,40,41)) && rand(0,99) < get_skill486prob($pd)) $ret = true;
		}
		return $ret;
	}
	
	//记录复活次数，发复活状况
	function post_revive_events(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $rkey);
		if('skill486' == $rkey){
			\skillbase\skill_setvalue(486,'rtime', \skillbase\skill_getvalue(486,'rtime',$pd) + 1,$pd);
			$pd['skill486rivv'] = 1;
			$pd['rivival_news'] = array('skill486_revv', $pd['name']);
			//addnews ( 0, 'skill486_revv', $pd['name'] );
		}
		return;
	}
	
	//发$log通告
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa,$pd,$active);
		
		eval(import_module('sys','logger'));
		//现在这里只需要发$log通告
		if(!empty($pd['skill486rivv'])){
			$tmp_log = "<span class='lime b'>然而，<:pd_name:>奇迹般从致命一击中活了下来，还剩下一口气。</span><br>";
			$log .= \battle\battlelog_parser($pa, $pd, $active, $tmp_log);
			$pd['battlelog'] .= \battle\battlelog_parser($pa, $pd, 1-$active, $tmp_log);
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($news == 'skill486_revv') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}奇迹般地逃过一劫，活了下来！</span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>