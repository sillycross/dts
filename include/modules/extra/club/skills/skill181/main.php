<?php

namespace skill181
{
	$lvupss = $lvupssref = 0;//记录升级增加歌魂上限和恢复歌魂值的临时变量
	
	$skill181_init_ss = 50;
	
	function init() 
	{
		define('MOD_SKILL181_INFO','club;hidden;');
		eval(import_module('clubbase','skillbase'));
		$clubskillname[181] = '音感';

		foreach(Array(0, 4, 6, 10, 13, 14, 15, 16, 18, 19) as $i) {//大部分模式开局追加音感
			if(!isset($valid_skills[$i])) {
				$valid_skills[$i] = array();
			}
			$valid_skills[$i][] = 181;
		}
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
		if (\skillbase\skill_query(181,$pa) && ($pa['club'] != 17))
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
		if (\skillbase\skill_query(181,$pa) && ($pa['club'] != 17) && $lvupss)
		{
			eval(import_module('sys','player','logger'));
			$pa['mss'] += $lvupss;
			$pa['ss'] += $lvupss;
			if ($pa['ss'] + $lvupssref >= $pa['mss'])
			{
				$lvupssref = $pa['mss'] - $pa['ss'];
			}
			if($lvupssref < 0) $lvupssref = 0;
			$pa['ss'] += $lvupssref;
			
			$tmp_log = "<span class=\"yellow b\">你的歌魂上限增加了{$lvupss}".(!empty($lvupssref) ? "，歌魂恢复了{$lvupssref}" : '')."！</span><br>";
			if ($pa['pid'] === $pid)
			{
				$log .= $tmp_log;
			}
			elseif (!$pa['type'])
			{
				\logger\logsave($pa['pid'], $now, $tmp_log, 's');
			}
			$lvupss = $lvupssref = 0;
		}
		return $r;
	}

}

?>