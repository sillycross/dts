<?php

namespace skill714
{
	function init() 
	{
		define('MOD_SKILL714_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[714] = '告春';	
	}
	
	function acquire714(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost714(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked714(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		eval(import_module('sys'));
		if (\skillbase\skill_query(714, $pa))
		{
			if(empty($gamevars['sk714_flag']))
			{
				$gamevars['sk714_flag'] = 1;
				save_gameinfo();
			}
			addnews(0, 'skill714', $pa['name']);
		}
	}

	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		eval(import_module('sys'));
		if (!empty($gamevars['sk714_flag'])) array_push($ret, rand(0, 1) ? 'g' : 'l');
		return $ret;
	}
	
	function add_once_area($atime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		if(!empty($gamevars['sk714_flag']))
		{
			$gamevars['sk714_flag'] = 0;
			save_gameinfo();
		}
		$chprocess($atime);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'skill714') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}带来了春天的消息！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
