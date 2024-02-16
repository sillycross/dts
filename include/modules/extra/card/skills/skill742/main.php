<?php

namespace skill742
{
	function init()
	{
		define('MOD_SKILL742_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[742] = '次品';
	}
	
	function acquire742(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(742,'lvl','0',$pa);
	}
	
	function lost742(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(742,'lvl',$pa);
	}
	
	function check_unlocked742(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function wele()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('player'));
		if (\skillbase\skill_query(742, $sdata) && (int)\skillbase\skill_getvalue(742,'lvl',$sdata) == 1 && strpos($wep,'电气') !== false)
		{
			eval(import_module('logger'));
			$log .= "在你摆弄完你的武器之后，你的武器开始漏电了！<br>";
			$wepsk = \itemmain\replace_in_itmsk('^dp','',$wepsk);
			$wepsk .= '^dp1';
		}
	}
		
	function wpoison()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('player'));
		if (\skillbase\skill_query(742, $sdata) && (int)\skillbase\skill_getvalue(742,'lvl',$sdata) == 2 && strpos($wep,'毒性') !== false)
		{
			eval(import_module('logger'));
			$log .= "在你摆弄完你的武器之后，你觉得它看起来非常诱人。<br>";
			$wepsk = \itemmain\replace_in_itmsk('^dp','',$wepsk);
			$wepsk .= '^dp2';
		}
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (rand(0,99) < 25)
		{
			if (\itemmain\check_in_itmsk('e', $pa['wepsk']) && \itemmain\check_in_itmsk('^dp', $pa['wepsk']) == 1)
			{
				eval(import_module('logger'));
				if ($active) $log .= "你因为武器漏电而<span class=\"yellow b\">身体麻痹</span>了！<br>";
				else $log .= "{$pa['name']}因为武器漏电而<span class=\"yellow b\">身体麻痹</span>了！<br>";
				\wound\get_inf('e', $pa);
			}
			elseif (\itemmain\check_in_itmsk('p', $pa['wepsk']) && \itemmain\check_in_itmsk('^dp', $pa['wepsk']) == 2)
			{
				eval(import_module('logger'));
				if ($active) $log .= "你习惯性地舔了一下自己的武器，然后<span class=\"purple b\">中毒</span>了！<br>";
				else $log .= "{$pa['name']}习惯性地舔了一下自己的武器，然后<span class=\"purple b\">中毒</span>了！<br>";
				\wound\get_inf('p', $pa);
			}
		}
		$chprocess($pa, $pd, $active);
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($cinfo);
		if($ret) {
			if ('^dp' == $cinfo[0]) return false;
		}
		return $ret;
	}
	
}

?>