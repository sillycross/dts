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
			$noiseinfo['KARMA']='■';
			$noiseinfo['HWEIHOA']='驱寒颂歌';
		}
	}
	
	function ss_sing($sn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger','noise','song'));
		$songcfg = NULL;
		foreach($songlist as $sval){
			if($sval['songname'] == $sn) {
				$songcfg = $sval;
				break;
			}
		}
		if(!$songcfg) {
			$log .= '好像不存在这样一首歌呢……<br>';
			return;
		}
		$r=$songcfg['cost'];
		$nkey = $songcfg['noisekey'];

		if ($ss>=$r){
			$ss-=$r;
			$log.="消耗<span class=\"yellow\">{$r}</span>点歌魂，歌唱了<span class=\"yellow\">{$noiseinfo[$nkey]}</span>。<br>";
		}else{
			$log.="需要<span class=\"yellow\">{$r}</span>歌魂才能唱这首歌！<br>";
			return;
		}
		addnews($now,'song',$name,$plsinfo[$pls],$songcfg['songname']);
		foreach($songcfg['lyrics'] as $lyric){
			$log .= $lyric.'<br>';
			\sys\addchat(0, $lyric, $name);
		}
		if (defined('MOD_NOISE') && !empty($nkey)) \noise\addnoise($pls,$nkey,$pid);
		
		$songqry = '';
		foreach($songcfg['effect'] as $sekey => $seval){
			if(isset($sdata[$sekey])){//如果不存在这个数值就无视掉
				$qry_sign=NULL;
				if(strpos($seval,'=')===0){
					$seval = (int)substr($seval,1);
					${$sekey} = $seval;
					$ef_sign = '<span class="yellow">变成</span>';
					$qry_sign = '=';
				}elseif((int)$seval > 0){
					$seval = (int)$seval;
					${$sekey} += $seval;
					$ef_sign = '<span class="lime">增加</span>';
					$qry_sign = '+';
				}elseif((int)$seval < 0){
					$seval = (int)$seval;
					${$sekey} += $seval;
					$ef_sign = '<span class="red">减少</span>';
					$qry_sign = '-';
				}
				//这里最好和lang.php合并一下
				if($sekey == 'att') $ef_word = '攻击力';
				elseif($sekey == 'def') $ef_word = '防御力';
				elseif($sekey == 'hp') $ef_word = '生命';
				elseif($sekey == 'mhp') $ef_word = '最大生命';
				elseif($sekey == 'sp') $ef_word = '体力';
				elseif($sekey == 'msp') $ef_word = '最大体力';
				elseif($sekey == 'ss') $ef_word = '歌魂';
				elseif($sekey == 'mss') $ef_word = '最大歌魂';
				elseif($sekey == 'rp') $ef_word = 'RP';
				elseif($sekey == 'money') $ef_word = '金钱';
				else $ef_word = $sekey;
				if($qry_sign){
					$log .= "歌声让你以及附近的玩家的{$ef_word}{$ef_sign}了".abs($seval)."。<br>";
					$songqry .= $qry_sign == '=' ? $sekey.'='.$seval.',' : $sekey.'='.$sekey.$qry_sign.$seval.',';
				}
			}
		}
		if(!empty($songqry)){
			$songqry = substr($songqry,0,-1);
			$db->query ("UPDATE {$tablepre}players SET ".$songqry." WHERE `pls` ={$pls} AND hp>0 AND type=0");
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
			if($ss+$itme <= $mss) $ss+=$itme;//现有歌魂加完以后不会超限时，也增加物品的效果值
			elseif($ss <= $mss) $ss = $mss;//现有歌魂加完以后会超限，加到最大歌魂
			//现有歌魂已经比最大歌魂大时，不加
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
			return;
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
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'song') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}</span>在<span class=\"yellow\">{$b}</span>歌唱了<span class=\"red\">{$c}</span>。</li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
