<?php

namespace ex_alternative
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['^alt'] = '两用';
		$itemspkdesc['^alt'] = '这一道具能当做<:skn:>使用';
	}
	
	function get_altitmk($itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//该函数没有类别是否合法的检测
		$altitmk = \itemmain\check_in_itmsk('^alt', $itmsk);
		$dict = ')!@#$%-~*(';//对应键盘数字键的上标符号，其中^替换成-，&替换成~
		for($i=0;$i<=9;$i++){
			$altitmk = str_replace($dict[$i], $i, $altitmk);
		}
		return $altitmk;
	}
		
	function swap_altitmk(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$itmk=&$theitem['itmk'];
		$itmsk=&$theitem['itmsk'];	
		$altitmk = get_altitmk($itmsk);
		if (!empty($altitmk))
		{
			$itmsk = \itemmain\replace_in_itmsk('^alt','',$itmsk);
			//为什么会有游戏王和剧毒？
			$dict = ')!@#$%-~*(';//对应键盘数字键的上标符号，其中^替换成-，&替换成~
			for($i=0;$i<=9;$i++){
				$itmk = str_replace($i, $dict[$i], $itmk);
			}
			$itmsk .= '^alt_'.$itmk.'1';
		}
		$itmk = $altitmk;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		//如果是装着箭的弓或者装着外甲的防具，不会触发两用
		if (!\itemmain\check_in_itmsk('^ari', $itmsk) && !\itemmain\check_in_itmsk('^su', $itmsk) && \itemmain\check_in_itmsk('^alt', $itmsk)) 
		{
			$alternative_choice = get_var_in_module('alternative_choice', 'input');
			if (empty($alternative_choice))
			{
				ob_start();
				include template(MOD_EX_ALTERNATIVE_USE_ALTERNATIVE);
				$cmd = ob_get_contents();
				ob_end_clean();	
				return;
			}
			else
			{
				if (1 == $alternative_choice)
				{
					$chprocess($theitem);
				}
				elseif (2 == $alternative_choice)
				{
					$altitmk = get_altitmk($itmsk);
					$altitmk_words = \itemmain\parse_itmk_words($altitmk);
					$log .= "你把<span class=\"yellow b\">$itm</span>当做了<span class=\"yellow b\">$altitmk_words</span>使用。<br>";
					swap_altitmk($theitem);
					$chprocess($theitem);
				}
				else
				{
					$log .= '参数不合法。<br>';
					$mode = 'command';
					return;
				}
			}
		}
		else $chprocess($theitem);
	}
	
	function get_itmsk_desc_single_comp_process($skk, $skn, $sks)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$skn = $chprocess($skk, $skn, $sks);
		if(strpos($skk, '^alt')===0) {
			$dict = ')!@#$%-~*(';
			for($i=0;$i<=9;$i++){
				$sks = str_replace($dict[$i], $i, $sks);
			}
			$skn = \itemmain\parse_itmk_words($sks);
		}
		return $skn;
	}
	
}

?>
