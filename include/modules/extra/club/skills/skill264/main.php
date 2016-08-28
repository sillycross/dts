<?php

namespace skill264
{
	$sk264_words = Array(
		'“怎么，你不服？”',
		'“今天我只是想被各位打死，或者打死各位。”',
		'“我这一拳下去，你可能会死。”',
		'“我是说，在座的各位都是垃圾！”',
		'“你能反击0次！”',
		'“我还能打十个！”'
	);
	
	function init() 
	{
		define('MOD_SKILL264_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[264] = '连打';
	}
	
	function acquire264(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost264(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked264(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=15;
	}
	
	function attack(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		
		if (\skillbase\skill_query(264,$pa) && check_unlocked264($pa) && $pa['wepk']=='WN')
		{
			while (rand(0,99)<30)
			{
				eval(import_module('logger','skill264'));
				$log.='<span class="clan">'.$sk264_words[rand(0,count($sk264_words)-1)].'</span><br>';
				$chprocess($pa,$pd,$active);
			}
		}
	}
}

?>
