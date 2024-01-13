<?php

namespace ex_alternative
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['^alt'] = '多态';//现在不显示了
		$itemspkdesc['^alt'] = '这一道具能当做其他类别、名称或属性使用';
		$itemspkremark['^alt'] = '游戏中不会显示。<br>在使用时会额外显示一个列表，让玩家决定当做哪个类别、名称或属性使用。';
		$itemspkinfo['^atype'] = '可改变哪一项';//不显示，0:类别；1:名称；2:属性
	}
	
	function get_altlist($itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//该函数没有类别或属性是否合法的检测
		$alts = \itemmain\check_in_itmsk('^alt', $itmsk);	
		if (empty($alts)) return array();
		$alts = \attrbase\base64_decode_comp_itmsk($alts);
		$altlist = explode(',', $alts);
		return $altlist;
	}
	
	//encode前形似WC,WP,WK这样，用半角逗号分割
	//属性切换不需要在切换的属性字符串里写^alt和^atype
	function alt_change(&$theitem, $atype, $idx = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$itm=&$theitem['itm'];
		$itmk=&$theitem['itmk'];
		$itmsk=&$theitem['itmsk'];
		if (2 == $atype) $key = 'itmsk';
		elseif (1 == $atype) $key = 'itm';
		else $key = 'itmk';
		$altlist = get_altlist($itmsk);
		if (!empty($altlist))
		{
			$itmsk = \itemmain\replace_in_itmsk('^alt','',$itmsk);
			if (2 == $atype) $itmsk = \itemmain\replace_in_itmsk('^atype','',$itmsk);
			swap($theitem[$key], $altlist[$idx]);
			$alts = implode(',', $altlist);
			$itmsk .= '^alt_'.\attrbase\base64_encode_comp_itmsk($alts).'1';
			if (2 == $atype) $itmsk .= '^atype2';
		}
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		//如果是装着箭的弓或者装着外甲的防具，不会触发变化
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
				else
				{
					$altlist = get_altlist($itmsk);
					if ($alternative_choice > count($altlist) + 1)
					{
						$log .= '参数不合法。<br>';
						$mode = 'command';
						return;
					}
					$atype = (int)\itemmain\check_in_itmsk('^atype', $itmsk);
					$altwords = get_altwords($altlist[$alternative_choice-2], $atype, 1);
					$log .= "你把<span class=\"yellow b\">$itm</span>当做了<span class=\"yellow b\">$altwords</span>使用。<br>";
					alt_change($theitem, $atype, $alternative_choice - 2);
					$chprocess($theitem);
				}
			}
		}
		else $chprocess($theitem);
	}
	
	//$atype: 0:类别；1:名称；2:属性，$suf: 是否加后缀（属性、类别），默认不显示
	function get_altwords($alts, $atype, $suf = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (2 == $atype)
		{
			//先把变化属性抹掉
			$alts = \itemmain\replace_in_itmsk('^alt','',$alts);
			//可以切换成白板道具，真会出现这样的情况吗？总之先考虑进去了
			$altwords = !empty($alts) ? \itemmain\parse_itmsk_words($alts,1) : '';
			if (empty($altwords)) $altwords = '无';
			if ($suf) $altwords .= '属性';
		}
		elseif (1 == $atype)
		{
			$altwords = $alts;
		}
		else
		{
			$altwords = \itemmain\parse_itmk_words($alts);
			if ($suf) $altwords .= '类别';
		}
		return $altwords;
	}	
	
	// function get_itmsk_desc_single_comp_process($skk, $skn, $sks)
	// {
		// if (eval(__MAGIC__)) return $___RET_VALUE;
		// $skn = $chprocess($skk, $skn, $sks);
		// if(strpos($skk, '^alt')===0) {
			// $dict = ')!@#$%-~*(';
			// for($i=0;$i<=9;$i++){
				// $sks = str_replace($dict[$i], $i, $sks);
			// }
			// $skn = \itemmain\parse_itmk_words($sks);
		// }
		// return $skn;
	// }
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		$ret = $chprocess($cinfo);
		if ($ret) {
			if (strpos($cinfo[0], '^alt') === 0) return false;
			if ('^atype' == $cinfo[0]) return false;
		}
		return $ret;
	}
	
}

?>
