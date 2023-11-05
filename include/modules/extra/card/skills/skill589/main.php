<?php

namespace skill589
{
	function init() 
	{
		define('MOD_SKILL589_INFO','card;active;');
		eval(import_module('clubbase'));
		$clubskillname[589] = '地异';	
	}
	
	function acquire589(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost589(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked589(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=12;
	}

	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (get_var_in_module('mode', 'sys') == 'special' && get_var_in_module('command', 'sys') == 'skill589_special' && get_var_in_module('subcmd', 'input') == 'castsk589') 
		{
			cast_skill589();
			return;
		}
		$chprocess();
	}

	function cast_skill589()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(589)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		if (\skillbase\skill_getvalue(589,'activated')) 
		{
			$log .= '你已经发动过「地异」了！<br>';
			return;
		}
		
		if(empty($gamevars['sk589_flag']))
		{
			$gamevars['sk589_flag'] = 1;
			save_gameinfo();
		}
		$log .= "你发动技能「地异」，改变了虚拟战场的地形！！<br>";
		\skillbase\skill_setvalue(589,'activated',1);
		addnews(0, 'skill589', $name);
		$mode = 'command';
		return;
	}
	
	function move($moveto = 99) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger'));
		if(!empty($gamevars['sk589_flag']))
		{
			$o_moveto = $moveto;
			if ($moveto > 0) $moveto = count($plsinfo) - $moveto;//无月例外
			if ($moveto != $pls)
			{
				$log .= "你循着记忆前往{$plsinfo[$o_moveto]}，但发现战场地形已经被大幅改变了。<br>究竟是谁干的好事？<br>";
				if (\skillbase\skill_query(589) && \skillbase\skill_getvalue(589,'activated')) $log .= "<span class=\"yellow b\">……大概是我自己？那没事了！</span><br>";
				$log .= "<br>";
			}
		}
		
		$chprocess($moveto);
	}
	
	function add_once_area($atime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		if(!empty($gamevars['sk589_flag']))
		{
			$gamevars['sk589_flag'] = 0;
			save_gameinfo();
		}
		$chprocess($atime);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'skill589') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「地异」</span>，改变了虚拟战场的地形！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
