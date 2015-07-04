<?php

namespace blessstone
{
	function init()
	{
		eval(import_module('itemmain'));
		$itemspkinfo['Z'] = '菁英';
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0)
			if ($itm == '『灵魂宝石』' || $itm == '『祝福宝石』')
			{
				$cmd = '<input type="hidden" name="mode" value="item"><input type="hidden" name="usemode" value="blessstone"><input type="hidden" name="itmp" value="' . $theitem['itmn'] . '">你想强化哪一件装备？<br><input type="radio" name="command" id="menu" value="menu" checked><a onclick=sl("menu"); href="javascript:void(0);" >返回</a><br>';
				for($i = 1; $i <= 6; $i ++) {
					if ((strpos ( ${'itmsk' . $i}, 'Z' ) !== false) && (strpos ( ${'itm' . $i}, '宝石』' ) === false)) {
						$cmd .= '<input type="radio" name="command" id="itm' . $i . '" value="itm' . $i . '"><a onclick=sl("itm' . $i . '"); href="javascript:void(0);" >' . "${'itm'.$i}/${'itme'.$i}/${'itms'.$i}" . '</a><br>';
						$flag = true;
					}
				}
				$cmd .= '<br><input type="button" onclick="postCmd(\'gamecmd\',\'command.php\');" value="提交">';
				if (! $flag) {
					$log .='唔？你的包裹里没有可以强化的装备，是不是没有脱下来呢？DA☆ZE<br><br>';
				}else{
					$log .="宝石在你的手上发出异样的光芒，似乎有个奇怪的女声在你耳边说道<span class=\"yellow\">\"我是从天界来的凯丽\"</span>.";
				}				
				return;
			}
		
		$chprocess($theitem);
	}
	
		
	function use_blessstone($itmn = 0) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger','input'));
		
		$itmn = (int)$itmn;
		$baoshi = & ${'itm'.$itmp};
		$baoshie = & ${'itme'.$itmp};
		$baoshis = & ${'itms'.$itmp};
		$baoshik = & ${'itmk'.$itmp};
		$baoshisk = & ${'itmsk'.$itmp};	
		
		if ( $itmn < 1 || $itmn > 6 ) {
			$log .= '此道具不存在，请重新选择。';
			$mode = 'command';
			return;
		}
		
		$itm = & ${'itm'.$itmn};
		$itme = & ${'itme'.$itmn};
		$itms = & ${'itms'.$itmn};
		$itmk = & ${'itmk'.$itmn};
		$itmsk = & ${'itmsk'.$itmn};
		
		if($baoshis <= 0 || ($baoshi != '『灵魂宝石』' && $baoshi != '『祝福宝石』')) {
			$log .= '强化道具选择错误，请重新选择。<br>';
			$mode = 'command';
			return;
		}
		if(!$itms || strpos ( $itmsk, 'Z' ) === false) {
			$log .= '被强化道具选择错误，请重新选择。<br>';
			$mode = 'command';
			return;
		}
		
		$o_itm = $itm;
		if(!preg_match("/\[\+[0-9]\]/",$itm)){
			$itm = ${'itm'.$itmn}.'[+0]';
			$flag = true;
			$zitmlv = 0;
		}else{
			preg_match("/\[\+([0-9])\]/",$itm,$zitmlv);
			$zitmlv = $zitmlv[1];
			if($zitmlv >= 4 && $baoshi != '『灵魂宝石』'){
				$log .= '你所选的宝石只能强化装备到[+4]哦!DA☆ZE<br>';
			$mode = 'command';
				return;
			}else{
				if (($zitmlv==3)&&($baoshi=='『祝福宝石』')){
					$gailv = rand(1,3);
				}elseif ($zitmlv >= 4){
					$gailv = rand(1,$zitmlv-2);
				}elseif ($zitmlv >= 6){
					$gailv = rand(1,$zitmlv-1);
				}elseif ($zitmlv >= 10){
					$gailv = rand(1,$zitmlv);
				}else{
					$gailv = 1;
				}
				if ($gailv == 1 ){
					$flag = true;
				}else{$flag = false;}
			}	
		}	
		addnews ( $now, 'newwep2', $name, $baoshi, $o_itm );
		if ($flag){
			$log .= "<span class=\"yellow\">『一道神圣的闪光照耀在你的眼睛上，当你恢复视力时，发现你的装备闪耀着彩虹般的光芒』</span><br>";
			$nzitmlv = $zitmlv +1;
			$itm = str_replace('[+'.$zitmlv.']','[+'.$nzitmlv.']',$itm);
			$itme = round($itme * (1.5 + 0.1 * $zitmlv));
		}else{
			$itm = "悲叹之种";
			$itme = 1;
			$itms = 1;
			$itmk = 'X';
			$itmsk = '';
			$log .="<span class=\"yellow\">『一道神圣的闪光照耀在你的眼睛上，当你恢复视力时，发现你的装备变成了{$itm}』</span><br>";
		}			
		$baoshis--;
		if($baoshis <= 0){
			$log .= "<span class=\"red\">$baoshi</span> 用光了。<br>";
			$baoshi = $baoshik = $baoshisk = '';$baoshie = $baoshis = 0;
		}	
		$mode = 'command';
		return;
	}	
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','input'));
		if($mode == 'item' && $usemode == 'blessstone') 
		{
			$item = substr($command,3);
			use_blessstone($item);
			return;
		}
		
		$chprocess();
	}
	
	function use_stone($itm, $itme)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('player','logger'));
		if(strpos ( $wepsk, 'Z' ) !== false){
			$log .= '咦……刀刃过于薄了，感觉稍微磨一点都会造成不可逆的损伤呢……<br>';
			return 0;
		}
		return $chprocess($itm,$itme);
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if($news == 'newwep2') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}使用了{$b}，强化了<span class=\"yellow\">$c</span>！</span><br>\n";
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
}

?>
