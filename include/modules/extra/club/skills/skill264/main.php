<?php

namespace skill264
{
	$sk264_words = Array(
		'“蒸馍，你不服气？”',
		'“今天我只是想被各位打死，或者打死各位。”',
		'“我这一拳下去，你可能会死。”',
		'“我是说，在座的各位都是垃圾！”',
		'“你没有下一回合了！”',
		'“我还能打十个！”',
		'“来来来，战个痛快！”'
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
	
	function assault(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		
		if (\skillbase\skill_query(264,$pa) && check_unlocked264($pa) && $pa['user_commanded']==1 && $pa['wepk']=='WN' && $pd['type']==0 && $pd['counter_assaulted'])
		{
			while (rand(0,99)<30)
			{
				$pa['sk262flag']=1;	//不再触发蓄力
				$bskill = & get_var_input('bskill');
				$bskill = 0;//不再发动乱击
				eval(import_module('logger','skill264'));
				$log.='<span class="cyan b">'.$sk264_words[rand(0,count($sk264_words)-1)].'</span><br>';
				$chprocess($pa,$pd,$active);
			}
		}
	}
}

?>