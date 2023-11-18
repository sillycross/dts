<?php

namespace skill701
{
	$skill701_recordnum = 200;
	$skill701_lvllimit = 30;
	
	function init() 
	{
		define('MOD_SKILL701_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[701] = '闻持';
	}
	
	function acquire701(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost701(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked701(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calc_memory_recordnum(&$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = &$sdata;
		}
		if (\skillbase\skill_query(701,$pa) && check_unlocked701($pa)) 
		{
			eval(import_module('skill701'));
			return $skill701_recordnum;
		}
		return $chprocess($pa);
	}
	
	function post_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('player'));
		if (\skillbase\skill_query(701,$sdata) && check_unlocked701($sdata))
		{
			eval(import_module('skill701'));
			if (($lvl >= $skill701_lvllimit) && ($hp > 0))
			{
				$hp = 0;
				$state = 49;
				\player\update_sdata();
				$sourceless = 1;
				\player\kill($sdata,$sdata);
				eval(import_module('logger'));
				$log .= "<span class=\"red b\">你没有多余的时间了！</span><br>";
				return;
			}
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if(isset($exarr['dword'])) $e0 = $exarr['dword'];

		if($news == 'death49') {
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span><span class=\"red b\">度入了轮回</span>$e0</li>";
		} else return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>