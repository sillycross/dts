<?php

namespace item_uys
{
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['YS'] = '防具强化';
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
		eval(import_module('sys','player','itemmain','logger'));
		
		$itmn = (int)$itmn;
		$itmp = (int)get_var_input('itmp');
		$sknum = (int)get_var_input('sknum');
		
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
		$log .= "<span class=\"yellow b\">你开始强化{$itm}……</span><br>";
		//这种封装来封装去有点丑陋
		$theitem = Array('itm' => &$itm, 'itmk' => &$itmk, 'itme' => &$itme,'itms' => &$itms,'itmsk' => &$itmsk);
		$sewingkit = Array('itm' => &$sk, 'itmk' => &$skk, 'itme' => &$ske,'itms' => &$sks,'itmsk' => &$sksk);
		$sewing_results = Array();
		for ($i = 1; $i <= $sknum; $i++)
		{
			
			$log .= '<span id="autopower'.$i.'" style="display:none">';
			
			//单次处理，返回true为强化成功。注意在这里面会让针线包的耐久-1
			$sewing_results[] = autosewingkit_single($i, $theitem, $sewingkit, $sewing_results);
			
			if ($i == $sknum && $sks > 0) 
			{
				$log .= "<span class=\"yellow b\">什么都叠甲只会害了{$itm}。你按计划停止了强化。</span><br>";;
			}
			else $log .= '</span>';
			
			if ($sks <= 0)
			{
				break;//保底
			}
		}
		//所有强化完成后的处理。道具耗尽之后清空相关数据放在这里面。
		autosewingkit_finish_event($sewing_results, $theitem, $sewingkit);
		
		$log .= '<span id="autopower_curnum" style="display:none">1</span>';
		$log .= '<span id="autopower_totnum" style="display:none">'.$sknum.'</span>';
		$log .= '<span id="autopower_cd" style="display:none">'.round($itemusecoldtime).'</span>';
		\cooldown\set_coldtime(round($itemusecoldtime) * $sknum, true);
		$mode = 'command';
		return;
	}
	
	//单次强化的处理
	function autosewingkit_single($nowi, &$theitem, &$sewingkit, $sewing_results){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('itemmain','logger'));
		
		$sk = & $sewingkit['itm'];
		$ske = & $sewingkit['itme'];
		$sks = & $sewingkit['itms'];
		$skk = & $sewingkit['itmk'];
		$sksk = & $sewingkit['itmsk'];
		
		$itm = & $theitem['itm'];
		$itme = & $theitem['itme'];
		$itms = & $theitem['itms'];
		$itmk = & $theitem['itmk'];
		$itmsk = & $theitem['itmsk'];
		
		$itme += (rand (0, 2) + $ske);
		if($nosta !== $itms) {//如果不是无限耐，稍微增加一点耐久
			$itms_up = floor(min($ske / 10, $itms / 2));
			$itms += $itms_up;
		}
		
		//统计先前强化成功次数。注意本次强化也是成功的
		$last_success_num = 1;
		foreach($sewing_results as $v) {
			if($v) $last_success_num++;
		}
		
		$log .= "用<span class=\"yellow b\">$sk</span>给<span class=\"yellow b\">$itm</span>叠上了{$last_success_num}层附加装甲，<span class=\"yellow b\">$itm</span>的效果值变成了<span class=\"yellow b\">$itme</span>";
		if ($itms_up > 0)
		{
			$log .= "，耐久值变成了<span class=\"yellow b\">$itms</span>点";
		}
		$log .= "。<br>";
		$sks -= 1;
		
		//成功强化返回true，强化失败返回false
		return true;
	}
	
	//所有强化完成时的处理
	function autosewingkit_finish_event($sewing_results, &$theitem, &$sewingkit){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		if($sewingkit['itms'] <= 0) {			
			$log .= "<span class=\"red b\">{$sewingkit['itm']}</span>用光了。<br>";
			$sewingkit['itm'] = $sewingkit['itmk'] = $sewingkit['itmsk'] = '';
			$sewingkit['itme'] = $sewingkit['itms'] = 0;
		}
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		$usemode = get_var_input('usemode');
		if ($mode == 'item' && $usemode == 'sewingkit' && substr($command, 0, 3) == 'itm') 
		{
			$item = substr($command, 3);
			autosewingkit($item);
			return;
		}		
		$chprocess();
	}

}

?>