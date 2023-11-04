<?php

namespace skill181
{
	global $lvupss, $lvupssref;
	
	$skill181_init_ss = 50;
	
	function init() 
	{
		define('MOD_SKILL181_INFO','club;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[181] = '音感';
	}
	
	function acquire181(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill181'));
		$pa['mss'] += $skill181_init_ss;
		$pa['ss'] += $skill181_init_ss;
	}
	
	function lost181(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		if (\skillbase\skill_query(181,$pa))
		{
			eval(import_module('skill181'));
			$lvupss += rand(1,2);
			$lvupssref += rand(3,6);
		}
	}
	
	function checklvlup($v, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = $chprocess($v, $pa);
		eval(import_module('skill181'));
		if (\skillbase\skill_query(181,$pa) && $lvupss)
		{
			eval(import_module('sys','player','logger'));
			$pa['mss'] += $lvupss;		
			if ($pa['ss'] + $lvupssref >= $pa['mss'])
			{
				$lvupssref = $pa['mss'] - $pa['ss'];
			}
			$pa['ss'] += $lvupssref;
			
			if ($pa['pid'] === $pid)
			{
				$log .= "<span class=\"yellow b\">你的歌魂上限增加了{$lvupss}，歌魂恢复了{$lvupssref}！</span><br>";
			}
			elseif (!$pa['type'])
			{
				$w_log = "<span class=\"yellow b\">你的歌魂上限增加了{$lvupss}，歌魂恢复了{$lvupssref}！</span><br>";
				\logger\logsave($pa['pid'], $now, $w_log,'s');
			}
		}
		return $r;
	}

}

?>