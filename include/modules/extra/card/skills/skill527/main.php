<?php

namespace skill527
{
	
	function init() 
	{
		define('MOD_SKILL527_INFO','card;battle;');
		eval(import_module('clubbase'));
		$clubskillname[527] = '攻击';
	}
	
	function acquire527(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(527,'rmt',4,$pa);
	}
	
	function lost527(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked527(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function check_available527(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_remaintime527(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = \skillbase\skill_getvalue(527,'rmt',$pa);
		if(!$ret) $ret = 0;
		return $ret;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=527) {
			$chprocess($pa, $pd, $active);
			return;
		}
		$rmt = get_remaintime527($pa);
		
		eval(import_module('logger'));
		if (!\skillbase\skill_query(527,$pa) || !check_unlocked527($pa))
		{
			$log .= '你尚未获得这个技能！';
			$pa['bskill']=0;
		}
		elseif($rmt <= 0){
			$log .= '你的攻击性已经太强了，本局不能再使用这个技能了！';
			$pa['bskill']=0;
		}
		else
		{
			$pa['skill527_flag'] = 1;
			
		}
		$chprocess($pa, $pd, $active);
	}	
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		if (!empty($pa['skill527_flag']))
		{
			$rmt = get_remaintime527($pa);
			eval(import_module('logger'));
			$log .= \battle\battlelog_parser($pa, $pd, $active, "<span class=\"lime b\"><:pa_name:>发动了技能「攻击」让自己的基础攻击力翻倍了！</span><br>");
			$pa['att'] *= 2;
			$rmt -= 1;
			\skillbase\skill_setvalue(527,'rmt',$rmt,$pa);
			addnews ( 0, 'bskill527', $pa['name']);
		}
	}
	
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill527') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「攻击」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>