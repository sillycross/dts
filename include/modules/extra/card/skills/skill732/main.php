<?php

namespace skill732
{
	function init()
	{
		define('MOD_SKILL732_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[732] = '维修';
		//$itemspkinfo['^skflag'] = '技能道具标记';//不显示
	}
	
	function acquire732(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost732(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked732(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('player','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'Z' ) === 0) 
		{
			if (\skillbase\skill_query(732,$sdata) && check_unlocked732($sdata) && ($itm == '漏水的雪管' || $itm == '漏雪的水管' || $itm == '漏雪的雪管'))
			{
				$log .= "你修好了<span class=\"yellow b\">{$itm}</span>！<br>";
				if ((int)\itemmain\check_in_itmsk('^skflag', $itmsk) == 732)
				{
					$log .= "看起来真不错！……嗯，好像有什么不对？<br><span class=\"red b\">糟糕，水管爆炸了！</span><br><br>你费了好大工夫才从雪水中挣扎着爬出来，但身体和精神都受到了沉重的伤害。<br>";
					$hp = 1;
					$sp = 1;
					$mhp = max($mhp-rand(100,250), 1);
					$msp = max($msp-rand(100,250), 1);
					foreach(array('h','b','a','f','p','w') as $v)
					{
						$inf = str_replace($v,'',$inf);
					}
					$inf .= 'hbafpw';
				}
				else
				{
					$log .= "看起来真不错！你获得了<span class=\"yellow b\">30</span>点经验。<br>";
					\lvlctl\getexp(30, $sdata);
				}
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
				return;
			}
		}
		$chprocess($theitem);
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($cinfo);
		if($ret) {
			if('^skflag' == $cinfo[0]) return false;
		}
		return $ret;
	}
	
}

?>