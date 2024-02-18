<?php

namespace skill111
{
	$skill111_tempskill_time = 300;
	
	function init()
	{
		define('MOD_SKILL111_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[111] = '入梦';
	}
	
	function acquire111(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost111(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked111(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=11;
	}
	
	function rest($restcommand)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (($state == 1) && \skillbase\skill_query(111,$sdata) && check_unlocked111($sdata) && \skillbase\skill_query(107,$sdata))
		{
			eval(import_module('sys','logger','rest'));
			$resttime = $now - $endtime;
			$sanity = (int)\skillbase\skill_getvalue(107,'sanity',$sdata);
			//恢复理智判定
			$get_sanity_rate = ceil($resttime * $sanity / 5);
			$sangain = floor($get_sanity_rate / 100);
			$get_sanity_rate = $get_sanity_rate % 100;
			if (rand(0,99) < $get_sanity_rate) $sangain += 1; 
			$sangain = min($sangain, 7-$sanity);
			if ($sangain > 0)
			{
				$log .= "你恢复了<span class=\"yellow b\">$sangain</span>点理智值。<br>";
				\skillbase\skill_setvalue(107,'sanity',$sanity+$sangain,$sdata);
			}
			//获得新技能判定
			$get_newskill_rate = ceil($resttime * (9 - $sanity) / 10);
			$newskillcount = floor($get_newskill_rate / 100);
			$get_newskill_rate = $get_newskill_rate % 100;
			if (rand(0,99) < $get_newskill_rate) $newskillcount += 1; 
			if ($newskillcount > 0)
			{
				$log .= "<span class=\"yellow b\">你在梦中接触到了新的知识……</span><br>";
				for ($i=0;$i<$newskillcount;$i++)
				{
					$newskillid = \skill107\skill107_get_randskill($sdata);
					if (!empty($newskillid))
					{
						eval(import_module('clubbase','skill111'));
						$log .= "你习得了技能<span class=\"yellow b\">「{$clubskillname[$newskillid]}」</span>，持续时间<span class=\"cyan b\">$skill111_tempskill_time</span>秒！<br>";
						\skillbase\skill_setvalue($newskillid, 'tsk_expire', $now + $skill111_tempskill_time, $sdata);
					}
				}
			}
		}
		$chprocess($restcommand);
	}
	
}

?>