<?php

namespace song
{
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['ss'] = '歌词卡片';
		$iteminfo['HM'] = '歌魂增加';
		$iteminfo['HT'] = '歌魂恢复';
		if (defined('MOD_NOISE'))
		{
			eval(import_module('noise'));
			$noiseinfo['Crow Song']='Crow Song';
			$noiseinfo['Alicemagic']='Alicemagic';
		}
	}
	
	function ss_sing($sn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger'));
		
		$r=$arte;

		if ($ss>=$r){
			$ss-=$r;
			$log.="消耗<span class=\"yellow\">{$r}</span>点歌魂，歌唱了<span class=\"yellow\">{$noiseinfo[$sn]}</span>。<br>";
		}else{
			$log.="需要<span class=\"yellow\">{$r}</span>歌魂才能唱这首歌！<br>";
			return;
		}
		
		if ($sn=="Alicemagic"){
			$log.="♪你說過在哭泣之後應該可以破涕而笑♪<br>
						♪我們的旅行　我不會忘♪<br>
						♪施展魔法　為了不再失去　我不會說再見♪<br>
						♪再次踏出腳步之時　將在某一天到來♪<br>";
						
			$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('0','$now','$name','$plsinfo','♪你說過在哭泣之後應該可以破涕而笑♪')");
			
			$db->query ( "UPDATE {$tablepre}players SET def=def+30 WHERE `pls` ={$pls} AND hp>0 AND type=0 ");
			$def+=30;
			if (defined('MOD_NOISE')) \noise\addnoise($pls,$sn,-1,-1);
			addnews($now,'song',$name,$plsinfo[$pls],$sn);
			return;
			
		}elseif ($sn=="Crow Song"){
				$log.="♪从这里找一条路♪<br>
						♪找到逃离的生路♪<br>
						♪奏响激烈的摇滚♪<br>
						♪盯紧遥远的彼方♪<br>
						♪在这个连呼吸都难以为继的都市中♪<br>";
						
			$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('0','$now','$name','$plsinfo','♪从这里找一条路♪')");
			
			$db->query ( "UPDATE {$tablepre}players SET att=att+30 WHERE `pls` ={$pls} AND hp>0 AND type=0 ");
			$att+=30;
			if (defined('MOD_NOISE')) \noise\addnoise($pls,$sn,-1,-1);
			addnews($now,'song',$name,$plsinfo[$pls],$sn);
			return;
		}
		
		return;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		eval(import_module('sys','player','logger'));
		if (strpos ( $itmk, 'HM' ) === 0) {
			$mss+=$itme;
			$ss+=$itme;
			$log .= "你使用了<span class=\"red\">$itm</span>，增加了<span class=\"yellow\">$itme</span>点歌魂。<br>";
			\itemmain\itms_reduce($theitem);
			return;
		}elseif (strpos ( $itmk, 'HT' ) === 0) {
			$ssup=$itme;
			if ($ss < $mss) {
				$oldss = $ss;
				$ss += $ssup;
				$ss = $ss > $mss ? $mss : $ss;
				$oldss = $ss - $oldss;
				$log .= "你使用了<span class=\"red\">$itm</span>，恢复了<span class=\"yellow\">$oldss</span>点歌魂。<br>";
				\itemmain\itms_reduce($theitem);
			} else {
				$log .= '你的歌魂不需要恢复。<br>';
			}
		} 
		
		if (strpos ( $itmk, 'ss' ) === 0)
		{
			$eqp = 'art';
			$noeqp = '';
			
			if (($noeqp && strpos ( ${$eqp.'k'}, $noeqp ) === 0) || ! ${$eqp.'s'}) {
				${$eqp} = $itm;
				${$eqp.'k'} = $itmk;
				${$eqp.'e'} = $itme;
				${$eqp.'s'} = $itms;
				${$eqp.'sk'} = $itmsk;
				$log .= "装备了<span class=\"yellow\">$itm</span>。<br>";
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
			} else {
				swap(${$eqp},$itm);
				swap(${$eqp.'k'},$itmk);
				swap(${$eqp.'e'},$itme);
				swap(${$eqp.'s'},$itms);
				swap(${$eqp.'sk'},$itmsk);
				$log .= "卸下了<span class=\"red\">$itm</span>，装备了<span class=\"yellow\">${$eqp}</span>。<br>";
			}
			return;
		}
		
		$chprocess($theitem);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		if($mode == 'command' && $command == 'song') {
			$sname=trim(trim($art,'【'),'】');
			ss_sing($sname);
			return;
		}
		$chprocess();
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'song') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}</span>在<span class=\"yellow\">{$b}</span>歌唱了<span class=\"red\">{$c}</span>。<br>\n";
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
}

?>
