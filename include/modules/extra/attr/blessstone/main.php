<?php

namespace blessstone
{
	function init()
	{
		eval(import_module('itemmain'));
		$itemspkinfo['Z'] = '菁英';
		$itemspkdesc['Z']='带有此属性的物品可以用『祝福宝石』或『灵魂宝石』强化效果值';
		$itemspkremark['Z']='第4次强化需要用2个以上的『祝福宝石』，第5次及以上只能用『灵魂宝石』。<br>第5次及以上强化有可能失败，次数越高概率越大。<br>不能使用磨刀石或者补丁来强化这一装备。';
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
				$flag = false;
				for($i = 1; $i <= 6; $i ++) {
					if (\itemmain\check_in_itmsk('Z', ${'itmsk' . $i}) && strpos ( ${'itm' . $i}, '宝石』' ) === false) {
						$flag = true;
						break;
					}
				}
				if (! $flag) {
					$log .='唔？你的包裹里没有可以强化的装备，是不是没有脱下来呢？DA☆ZE<br><br>';
				}else{
					$log .="宝石在你的手上发出异样的光芒，似乎有个奇怪的女声在你耳边说道：<span class=\"yellow b\">“我是从天界来的凯丽。”</span>";
					ob_start();
					include template(MOD_BLESSSTONE_USE_BLESSSTONE);
					$cmd = ob_get_contents();
					ob_end_clean();
				}				
				return;
			}
		
		$chprocess($theitem);
	}
	
	function use_blessstone($itmn = 0) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger','input'));
		
		$itmn = (int)$itmn;
		$itmp = (int)$itmp;
		
		if ( $itmp < 1 || $itmp > 6 ) {
			$log .= '此道具不存在，请重新选择。';
			$mode = 'command';
			return;
		}
		
		$gem = & ${'itm'.$itmp};
		$geme = & ${'itme'.$itmp};
		$gems = & ${'itms'.$itmp};
		$gemk = & ${'itmk'.$itmp};
		$gemsk = & ${'itmsk'.$itmp};	
		
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
		
		if($gems <= 0 || ($gem != '『灵魂宝石』' && $gem != '『祝福宝石』')) {
			$log .= '强化道具选择错误，请重新选择。<br>';
			$mode = 'command';
			return;
		}
		if(!$itms || !\itemmain\check_in_itmsk('Z', $itmsk)) {
			$log .= '被强化道具选择错误，请重新选择。<br>';
			$mode = 'command';
			return;
		}
		
		$o_itm = $itm;
		if(!preg_match("/\[\+[0-9]+?\]/",$itm)){
			$itm = ${'itm'.$itmn}.'[+0]';
			$flag = true;
			$zitmlv = 0;
		}else{
			preg_match("/\[\+([0-9]+?)\]/",$itm,$zitmlv);
			$zitmlv = $zitmlv[1];
			if($zitmlv >= 4 && $gem != '『灵魂宝石』'){
				$log .= '你所选的宝石只能强化装备到[+4]哦!DA☆ZE<br>';
				$mode = 'command';
				return;
			}else{
				if (($zitmlv==3)&&($gem=='『祝福宝石』')){
					if ($gems<2)
					{
						$log .= '你需要至少2颗祝福宝石才能强化装备到[+4]哦!DA☆ZE<br>';
						$mode = 'command';
						return;
					}
					if ($gems==2) 	//两颗成功率1/3
					{
						$gems--;
						$dice = rand(1,30);
					}
					else 			//3颗必定成功
					{
						$gems -= 2;
						$dice = 1;
					}
				}elseif ($zitmlv >= 4){
					$dice = rand(1,10*($zitmlv-2));//+5概率10/20，+6概率10/30，+7概率10/40，+8概率10/50
//				}elseif ($zitmlv >= 6){
//					$dice = rand(1,10*($zitmlv-1));//+7概率10/50，+8概率10/60，+9概率10/70，+10概率10/80
//				}elseif ($zitmlv >= 10){
//					$dice = rand(1,10*$zitmlv);//+11概率10/100，+12概率10/110以此类推
				}else{
					$dice = 1;
				}
				if ($dice <= 10 ){
					$flag = true;
				}else{$flag = false;}
			}	
		}	
		addnews ( $now, 'newwep2', $name, $gem, $o_itm );
		if ($flag){
			$log .= "<span class=\"yellow b\">『一道神圣的闪光照耀在你的眼睛上，当你恢复视力时，发现你的装备闪耀着彩虹般的光芒』</span><br>";
			$nzitmlv = $zitmlv +1;
			$itm = str_replace('[+'.$zitmlv.']','[+'.$nzitmlv.']',$itm);
			$itme = round($itme * (1.5 + 0.1 * $zitmlv));
		}else{
			$itm = "悲叹之种";
			$itme = 1;
			$itms = 1;
			$itmk = 'X';
			$itmsk = '';
			$log .="<span class=\"yellow b\">『一道神圣的闪光照耀在你的眼睛上，当你恢复视力时，发现你的装备变成了{$itm}』</span><br>";
		}			
		$gems--;
		if($gems <= 0){
			$log .= "<span class=\"red b\">$gem</span>用光了。<br>";
			$gem = $gemk = $gemsk = '';$geme = $gems = 0;
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
	
	//菁英刀不能磨
	function use_hone($itm, $itme)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('player','logger'));
		if(\itemmain\check_in_itmsk('Z', $wepsk)){
			$log .= '咦……刀刃过于薄了，感觉稍微磨一点都会造成不可逆的损伤呢……<br>';
			return 0;
		}
		return $chprocess($itm,$itme);
	}
	
	//菁英属性打针线包会失去精英
	function use_sewing_kit(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		$ret = $chprocess($theitem);
		if($ret && \itemmain\check_in_itmsk('Z', $arbsk)) {
			$log .= "打上补丁之后，装备显得朴素多了。<br><span class='yellow b'>$arb</span>失去了<span class='yellow b'>{$itemspkinfo['Z']}</span>属性！<br>";
			$arbsk = str_replace('Z','',$arbsk);
		}
		return $ret;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if($news == 'newwep2') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}使用了{$b}，强化了<span class=\"yellow b\">$c</span>！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
