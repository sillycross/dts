<?php

namespace item_uys
{
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['YS'] = '针线包';
	}

	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','cooldown','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos($itmk, 'YS') === 0)
		{
			if (!$coldtimeon)
			{
				$log .= '服务器没有开启冷却时间，本功能不可用。<br>';
				return;
			}
			$flag = false;
			for($i = 1; $i <= 6; $i ++) {
				if (strpos(${'itmk'.$i}, 'D') === 0) {
					$flag = true;
					break;
				}
			}
			if (!$flag) $log .= '你的包裹里没有可以强化的防具。<br>';
			else
			{
				ob_start();
				include template(MOD_ITEM_UYS_USE_SEWINGKIT);
				$cmd = ob_get_contents();
				ob_end_clean();
			}				
			return;
		}	
		$chprocess($theitem);
	}
	
	function autosewingkit($itmn = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		eval(import_module('sys','player','itemmain','logger','input'));
		
		$itmn = (int)$itmn;
		$itmp = (int)$itmp;
		$sknum = (int)$sknum;
		
		if ($itmp < 1 || $itmp > 6) {
			$log .= '此道具不存在，请重新选择。';
			$mode = 'command';
			return;
		}
		
		$sk = & ${'itm'.$itmp};
		$ske = & ${'itme'.$itmp};
		$sks = & ${'itms'.$itmp};
		$skk = & ${'itmk'.$itmp};
		$sksk = & ${'itmsk'.$itmp};
		
		if ($itmn < 1 || $itmn > 6) {
			$log .= '此道具不存在，请重新选择。';
			$mode = 'command';
			return;
		}

		$itm = & ${'itm'.$itmn};
		$itme = & ${'itme'.$itmn};
		$itms = & ${'itms'.$itmn};
		$itmk = & ${'itmk'.$itmn};
		$itmsk = & ${'itmsk'.$itmn};
		
		if ($sks <= 0 || (0 !== strpos($skk, 'YS')))
		{
			$log .= '强化道具选择错误，请重新选择。<br>';
			$mode = 'command';
			return;
		}
		if (!$itms || (0 !== strpos($itmk, 'D')))
		{
			$log .= '被强化道具选择错误，请重新选择。<br>';
			$mode = 'command';
			return;
		}
		if ($sknum <= 0 || $sknum > $sks)
		{
			$log .= '道具数量有误。<br>';
			$mode = 'command';
			return;
		}
		
		eval(import_module('cooldown'));
		$log .= "<span class=\"yellow b\">你开始为防具打针线包……</span><br>";
		for ($i = 1; $i <= $sknum; $i++)
		{
			$itme += (rand (0, 2) + $ske);
			$itms_up = floor(min($ske / 10, $itms / 2));
			$log .= '<span id="autopower'.$i.'" style="display:none">';
			$log .= "用<span class=\"yellow b\">$sk</span>给<span class=\"yellow b\">$itm</span>打了补丁，<span class=\"yellow b\">$itm</span>的防御力变成了<span class=\"yellow b\">$itme</span>";
			if ($itms_up > 0)
			{
				$log .= "，耐久值增加了<span class=\"yellow b\">$itms_up</span>点";
				$itms += $itms_up;
			}
			$log .= "。<br>";
			$sks -= 1;
			if ($i == $sknum) 
			{
				$log .= "<span class=\"yellow b\">你打完了补丁。</span><br>";;
			}
			else $log .= '</span>';
			if ($sks <= 0)
			{
				$log .= "<span class=\"red b\">$sk</span>用光了。<br>";
				$sk = $skk = $sksk = '';
				$ske = $sks = 0;
			}
		}
		$log .= '<span id="autopower_curnum" style="display:none">1</span>';
		$log .= '<span id="autopower_totnum" style="display:none">'.$sknum.'</span>';
		$log .= '<span id="autopower_cd" style="display:none">'.round($itemusecoldtime).'</span>';
		\cooldown\set_coldtime(round($itemusecoldtime) * $sknum, true);
		$mode = 'command';
		return;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','input'));
		if ($mode == 'item' && $usemode == 'sewingkit') 
		{
			$item = substr($command, 3);
			autosewingkit($item);
			return;
		}		
		$chprocess();
	}

}

?>
