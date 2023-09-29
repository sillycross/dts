<?php

namespace itemmain
{

	function itms_reduce(&$theitem, $reducen = 1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if ($itms !== $nosta) {
			$itms -= $reducen;
			if ($itms <= 0) {
				$log .= "<span class=\"red b\">$itm</span>用光了。<br>";
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
			}
		}
	}
	
	function itemuse_wrapper($itmn) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		if ($itmn < 0 || $itmn > 6) {
			$log .= '此道具不存在，请重新选择。';
			$mode = 'command';
			return;
		}
		$theitem=Array();
		$theitem['itm'] = & ${'itm' . $itmn};
		$theitem['itmk'] = & ${'itmk' . $itmn};
		$theitem['itme'] = & ${'itme' . $itmn};
		$theitem['itms'] = & ${'itms' . $itmn};
		$theitem['itmsk'] = & ${'itmsk' . $itmn};
		$theitem['itmn'] = $itmn;
		
		if (($theitem['itms'] <= 0) && ($theitem['itms'] != $nosta)) {
			$theitem['itm'] = $theitem['itmk'] = $theitem['itmsk'] = '';
			$theitem['itme'] = $theitem['itms'] = 0;
			$log .= '此道具不存在，请重新选择。<br>';
			$mode = 'command';
			return;
		}
		itemuse($theitem);
		
		$mode = 'command';
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('logger'));
		
		$log .= "你使用了道具 <span class=\"yellow b\">{$theitem['itm']}</span> 。<br>但是什么也没有发生。<br>";
	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($news == 'itemuse') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}使用了{$b}</span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}
	
?>