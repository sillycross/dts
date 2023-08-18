<?php

namespace scpdrink
{
	function init() {}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0) {
			if ($itm == '装有H173的注射器') {
				$log .= '你考虑了一会儿，<br>把袖子卷了起来，给自己注射了H173。<br>';
				$deathdice = rand ( 0, 8191 );
				if ($club == 15){
					$log .= '你的身体里已经充满了病毒，什么也没发生。<br>';
				}elseif ($deathdice == 8191 || $club == 15) {
					$log .= '你突然感觉到一种不可思议的力量贯通全身！<br>';
					$wp = $wk = $wg = $wc = $wd = $wf = 3000;
					$att = $def = 5000;
					$club = 15;
					addnews ( $now, 'suisidefail', $name );
					$itm = $itmk = $itmsk = '';
					$itme = $itms = 0;
				} else {
					$state = 31;
					$log .= '你失去了知觉。<br>';
					\player\update_sdata(); $sdata['sourceless'] = 1; $sdata['attackwith'] = $itm;
					\player\kill($sdata,$sdata);
					\player\player_save($sdata);
					\player\load_playerdata($sdata);
				}
				return;
			} elseif (strpos($itm, '溶剂SCP-294')===0) {
			
				if (defined('MOD_CLUBBASE')) \clubbase\club_lost();
				
				if($itm == '溶剂SCP-294_PT_Poini_Kune'){
					$log .= '你考虑了一会儿，一扬手喝下了杯中冒着紫色幽光的液体。<br><span class="yellow b">你感到全身就像燃烧起来一样，不禁扪心自问这值得么？</span><br>';
					if ($mhp > 573){
						$up = rand (0, $mhp + $msp);
					} else{
						$up = rand (0, 573);
					}

					if($club == 17){
						$hpdown = $spdown = round($up * 1.5);
					}elseif($club == 13){
						$hpdown = $up+200;
						$spdown = $up;
					}else{
						$hpdown = $spdown = $up;
					}
					$wp += $up;$wk += $up;$wg += $up;$wc += $up;$wd += $up;$wf += $up;
					$rp += 500;
					
					$mhp = $mhp - $hpdown;
					$msp = $msp - $spdown;				
					$log .= '你的生命上限减少了<span class="yellow b">'.$hpdown.'</span>点，体力上限减少了<span class="yellow b">'.$spdown.'</span>点，而你的全系熟练度提升了<span class="yellow b">'.$up.'</span>点！<br>';
				} elseif ($itm == '溶剂SCP-294_PT_Arnval'){
					$log .= '你考虑了一会儿，一扬手喝下了杯中冒着白色气泡的清澈液体。<br><span class="yellow b">你感到全身就像燃烧起来一样，不禁扪心自问这值得么？</span><br>';
					if ($msp > 573){
						$up = rand (0, $msp * 1.5);
					} else{
						$up = rand (0, 573);
					}
					$mhp = $mhp + $up;
					$def = $def + $up;
					$down = $club == 17 ? round($up * 1.5) : $up;
					$rp += 200;
					$msp = $msp - $down;
					$att = $att - $down;
					
					$log .= '你的体力上限和攻击力减少了<span class="yellow b">'.$down.'</span>点，而你的生命上限和防御力提升了<span class="yellow b">'.$up.'</span>点！<br>';
				} elseif ($itm == '溶剂SCP-294_PT_Strarf') {
					$log .= '你考虑了一会儿，一扬手喝下了杯中冒着灰色气泡的清澈液体。<br><span class="yellow b">你感到全身就像燃烧起来一样，不禁扪心自问这值得么？</span><br>';
					if ($mhp > 573){
						$up = rand (0, $msp * 1.5);
					} else{
						$up = rand (0, 573);
					}
					$msp = $msp + $up;
					$att = $att + $up;
					$down = $club == 17 ? round($up * 1.5) : $up;
					$rp += 200;
					$mhp = $mhp - $down;
					$def = $def - $down;
					$log .= '你的生命上限和防御力减少了<span class="yellow b">'.$down.'</span>点，而你的体力上限和攻击力提升了<span class="yellow b">'.$up.'</span>点！<br>';
				} elseif ($itm == '溶剂SCP-294_PT_ErulTron') {
					$log .= '你考虑了一会儿，一扬手喝下了杯中冒着粉红光辉的液体。<br>你感到你整个人貌似变得更普通了点。<br>';
					$lvl = $exp = 0;
					$att = round($att * 0.8);
					$def = round($def * 0.8);
					$log .= '<span class="yellow b">你的等级和经验值都归0了！但是，你的攻击力和防御力也变得更加普通了。</span><br>';
				}
				if($att < 0){$att = 0;}
				if($def < 0){$def = 0;}
				if($hp > $mhp){$hp = $mhp;}
				if($sp > $msp){$sp = $msp;}
				$deathflag = false;
				if($mhp <= 0){$hp = $mhp =0;$deathflag = true;}
				if($msp <= 0){$sp = $msp =0;$deathflag = true;}
				if($deathflag){
					$log .= '<span class="yellow b">看起来你的身体无法承受药剂的能量……<br>果然这一点都不值得……<br></span>';
					$state = 34;
					\player\update_sdata(); $sdata['sourceless'] = 1; $sdata['attackwith'] = $itm;
					\player\kill($sdata,$sdata);
					\player\player_save($sdata);
					\player\load_playerdata($sdata);
				} else {
					addnews ( $now, 'notworthit', $name );
					$club = 17;
					if (defined('MOD_CLUBBASE')) \clubbase\club_acquire($club);
				}
				\itemmain\itms_reduce($theitem);
				return;
			}
		}
		
		$chprocess($theitem);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'death31') 
		{
			$dname = $typeinfo[$b].' '.$a;
			if(!$e)
				$e0="<span class=\"yellow b\">【{$dname} 什么都没说就死去了】</span><br>\n";
			else  $e0="<span class=\"yellow b\">【{$dname}：“{$e}”】</span><br>\n";
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>因L5发作自己挠破喉咙身亡！{$e0}</li>";
		} 
		
		if($news == 'death34') 
		{
			$dname = $typeinfo[$b].' '.$a;
			if(!$e)
				$e0="<span class=\"yellow b\">【{$dname} 什么都没说就死去了】</span><br>\n";
			else  $e0="<span class=\"yellow b\">【{$dname}：“{$e}”】</span><br>\n";
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>因摄入过量突变药剂，身体组织崩解而死！{$e0}</li>";
		} 
		
		if($news == 'notworthit') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}做出了一个他自己可能会后悔很长一段时间的决定。</span></li>";
		
		if($news == 'suisidefail') 
			return "<li><font style=\"background:url(img/backround4.gif) repeat-x\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}注射了H173，却由于RP太高进入了发狂状态！！</font></span><br>\n";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
