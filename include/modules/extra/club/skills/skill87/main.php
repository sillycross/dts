<?php

namespace skill87
{
	$skill87_init_ss = 50;
	
	function init()
	{
		define('MOD_SKILL87_INFO','club;feature;');
		eval(import_module('clubbase'));
		$clubskillname[87] = '歌姬';
		$clubdesc_h[25] = $clubdesc_a[25] = '初始歌魂增加50点，升级获得更多的歌魂上限和歌魂回复；<br>初始习得歌曲《Alicemagic》和《Crow Song》';
	}
	
	function acquire87(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill87'));
		$pa['mss'] += $skill87_init_ss;
		$pa['ss'] += $skill87_init_ss;
		\song\learn_song_process('Alicemagic');
		\song\learn_song_process('Crow Song');
	}
	
	function lost87(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked87(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		if (\skillbase\skill_query(87,$pa))
		{
			eval(import_module('skill181'));
			$lvupss += rand(2,4);
			$lvupssref += rand(3,7);
		}
	}

}

?>