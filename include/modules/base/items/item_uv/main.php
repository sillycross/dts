<?php

namespace item_uv
{
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['VO'] = '卡片礼物';
		$iteminfo['V'] = '技能书籍';
		$iteminfo['VS'] = '技能书籍';
		$iteminfo['VV'] = '全系书籍';
		$iteminfo['VP'] = '殴系书籍';
		$iteminfo['VK'] = '斩系书籍';
		$iteminfo['VG'] = '射系书籍';
		$iteminfo['VC'] = '投系书籍';
		$iteminfo['VD'] = '爆系书籍';
		$iteminfo['VF'] = '灵系书籍';
	}
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if ((strpos($k,'V')===0) && (strpos($k,'S')!==false)){
			eval(import_module('clubbase'));
			if(!empty($clubskillname[$sk])){
				$ret .= '使用后获得技能「'.$clubskillname[$sk].'」';
			}
			if ($sk == '249'){
				$ret .= '：埋设陷阱伤害增加';
			}elseif ($sk == '250') {
				$ret .= '：受到陷阱伤害减少';
			}elseif ($sk == '723') {
				$ret .= '：射系武器弹夹翻倍且自动装填弹药';
			}elseif ($sk == '738') {
				$ret .= '：可以选择减少移动和探索体力消耗，或增加移动和探索体力消耗并减少冷却时间';
			}elseif ($sk == '739') {
				$ret .= '：可用歌魂交换空歌魂上限';
			}
		}
		return $ret;
	}
	
	function calc_skillbook_efct($itme, $skcnt, $ws_sum)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$skill_minimum = 100;
		$skill_limit = 300;
		$dice = rand ( - 5, 5 );
		
		if ($ws_sum < $skill_minimum * $skcnt) {
			$vefct = $itme;
		} elseif ($ws_sum < $skill_limit * $skcnt) {
			$vefct = round ( $itme * (1 - ($ws_sum - $skill_minimum * $skcnt) / ($skill_limit * $skcnt - $skill_minimum * $skcnt)) );
		} else {
			$vefct = 0;
		}
		if ($vefct < 5) {
			if ($vefct < $dice) {
				$vefct = - $dice;
			}
		}
		return $vefct;
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','weapon','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'V' ) === 0) 
		{
			if ($itmk[1] == 'O')
				$log .= "你打开了<span class=\"red b\">$itm</span>。<br>";
			else
				$log .= "你阅读了<span class=\"red b\">$itm</span>。<br>";
				
			//特殊的技能书类型VS，效果是获得技能编号为itmsk的技能
			if (strpos ( substr($itmk,1), 'S' ) !== false)	//技能书
			{
				eval(import_module('clubbase'));
				$useflag = 0;
				$sk_kind = (int)$itmsk;
				if ($sk_kind<1) $sk_kind = 1;
				if (defined('MOD_SKILL'.$sk_kind) && $clubskillname[$sk_kind]!='')
				{
					if (\skillbase\skill_query($sk_kind))
					{
						$log.="你发现这本书就是昨天刚刚看过的那本，不打算继续看下去了。<br>";
					}
					else
					{
						$log.="你感觉受益匪浅。你获得了技能「<span class=\"yellow b\">".$clubskillname[$sk_kind]."</span>」，请前往技能界面查看。<br>";
						\skillbase\skill_acquire($sk_kind);
						$useflag = 1;
						//\itemmain\itms_reduce($theitem);
					}
				}
				else
				{
					$log.="技能书参数错误，这应该是一个BUG，请联系管理员。<br>";
					return;
				}
			}
			
			//特殊的技能书类型VO
			//效果是这样的：（如果编号用完了请用单个字母）
			//VO/VO1: 获得编号为$itmsk的卡片
			//VO2: 获得A/B/C卡片
			//     A=10% B=35% C=55%
			//VO3: 获得S/A/B卡片
			//     S=10% A=25% B=65%
			//VO4: 获得特殊/S/A卡片 特殊=编号为$itmsk的卡片 （目前就是为那张特殊卡片服务的）
			//     特殊=15% S=20% A=65%
			//VO5: 获得S级卡片
			//VO6: 获得A级卡片
			//VO7: 获得B级卡片
			//VO8: 获得C级卡片
			//VO9: 获得B/C级卡片
			//     B=30% C=70%
			//
			if (defined('MOD_CARDBASE') && $itmk[1] == 'O')	//卡片礼物
			{
				eval(import_module('cardbase'));
				if (strlen($itmk) == 2) $cardpresent_type = '1'; else $cardpresent_type = $itmk[2];
				$itmn = $theitem['itmn'];
				$cardpresent_desc = 'N/A';
				if ($cardpresent_type == '1') $cardpresent_desc = '获得卡片“'.$cards[(int)$itmsk]['name'].'”';
				if ($cardpresent_type == '2') $cardpresent_desc = '从中有机会获得'.$card_rarity_html['A'].'/'.$card_rarity_html['B'].'/'.$card_rarity_html['C'].'级卡片';
				if ($cardpresent_type == '3') $cardpresent_desc = '从中有机会获得'.$card_rarity_html['S'].'/'.$card_rarity_html['A'].'/'.$card_rarity_html['B'].'级卡片';
				if ($cardpresent_type == '4') $cardpresent_desc = '从中有机会获得特殊卡片“<span class="yellow b">'.$cards[(int)$itmsk]['name'].'</span>”，或一张'.$card_rarity_html['S'].'级或'.$card_rarity_html['A'].'级的卡片';
				if ($cardpresent_type == '5') $cardpresent_desc = '从中可以获得一张'.$card_rarity_html['S'].'级卡片';
				if ($cardpresent_type == '6') $cardpresent_desc = '从中可以获得一张'.$card_rarity_html['A'].'级卡片';
				if ($cardpresent_type == '7') $cardpresent_desc = '从中可以获得一张'.$card_rarity_html['B'].'级卡片';
				if ($cardpresent_type == '8') $cardpresent_desc = '从中可以获得一张'.$card_rarity_html['C'].'级卡片';
				if ($cardpresent_type == '9') $cardpresent_desc = '从中有机会获得'.$card_rarity_html['B'].'级或'.$card_rarity_html['C'].'级卡片';
				
				if ($cardpresent_desc == 'N/A')
				{
					$log.='物品代码配置错误，请联系管理员。<br>';
					return;
				}
				
				if ($itm == '博丽神社的参拜券')
				{
					eval(import_module('sys'));
					if ($now - $starttime >= 1200)
					{
						$log.='<span class="yellow b">博丽神社今天已经关门啦，下次请早点来吧。（这个道具必须在开局20分钟内使用）<br></span>';
						return;
					}
				}
				
				$subcmd = get_var_input('subcmd');
				if ($subcmd == 'flipcard')
				{
					$get_card_id = 0;
					if ($cardpresent_type == '1')
					{
						$get_card_id = (int)$itmsk;
					}
					else if ($cardpresent_type == '4' && rand(1,100)<=15)
					{
						$get_card_id = (int)$itmsk;
					}
					else 
					{
						if ($cardpresent_type == '2') $cardraw_pr = Array('S'=>0, 'A'=>10, 'B'=>35, 'C'=>55);
						if ($cardpresent_type == '3') $cardraw_pr = Array('S'=>10, 'A'=>25, 'B'=>65, 'C'=>0);
						if ($cardpresent_type == '4') $cardraw_pr = Array('S'=>24, 'A'=>76, 'B'=>0, 'C'=>0);
						if ($cardpresent_type == '5') $cardraw_pr = Array('S'=>100, 'A'=>0, 'B'=>0, 'C'=>0);
						if ($cardpresent_type == '6') $cardraw_pr = Array('S'=>0, 'A'=>100, 'B'=>0, 'C'=>0);
						if ($cardpresent_type == '7') $cardraw_pr = Array('S'=>0, 'A'=>0, 'B'=>100, 'C'=>0);
						if ($cardpresent_type == '8') $cardraw_pr = Array('S'=>0, 'A'=>0, 'B'=>0, 'C'=>100);
						if ($cardpresent_type == '9') $cardraw_pr = Array('S'=>0, 'A'=>0, 'B'=>30, 'C'=>70);
						$dice=rand(1,100); $kind='';
						foreach ($cardraw_pr as $key => $value)
						{
							if ($dice<=$value)
							{
								$kind=$key; break;
							}
							else
							{
								$dice-=$value;
							}
						}
						if ($kind=='')
						{
							$log.='物品代码配置错误，请联系管理员。<br>';
							return;
						}
						$get_card_id = $cardindex[$kind][rand(0,count($cardindex[$kind])-1)];
					}
					
					if ($get_card_id==0)
					{
						$log.='物品代码配置错误，请联系管理员。<br>';
						return;
					}
					
					$is_new = '';
					//$ext = '来自'.($room_prefix ? '房间' : '').'第'.$gamenum.'局的'.$itm.'。'; 
					//小房间的编号未必是历史记录的编号，因此小房间就不显示房间号了
					if($room_prefix) {
						$ext = '来自'.$gtinfo[$gametype].'的'.$itm.'。';
					}else{
						$ext = '来自第'.$gamenum.'局的'.$itm.'。';
					}
					if($cards[$get_card_id]['rare'] == 'A') $ext.='运气不错！';
					elseif($cards[$get_card_id]['rare'] == 'S') $ext.='一定是欧洲人吧！';
					//计算卡片碎闪等级
					$blink = \cardbase\get_card_calc_blink($get_card_id, $cudata);
					//真正获得卡片
					$is_new = \cardbase\get_card_message($get_card_id,$ext,$blink);
					//if(!empty($is_new)) $is_new = "<span class=\"L5 b\">NEW!</span>";;
					ob_clean();
					include template('MOD_CARDBASE_CARDFLIP_RESULT');
					$log .= ob_get_contents();
					ob_clean();
					
					$log.='<span class="yellow b">你获得了卡片「'.$cards[$get_card_id]['name'].'」！请前往“站内邮件”查收。</span><br>';
					
					addnews ( 0, 'VOgetcard', $name, $itm, $cards[$get_card_id]['name'] );
					
					\itemmain\itms_reduce($theitem);
					
					return;
				}
				else
				{
					ob_clean();
					include template('MOD_CARDBASE_CARDFLIP_BACK');
					$log .= ob_get_contents();
					ob_clean();
					return;
				}
			}
			
			//下面是普通的技能书处理（效果是加某个系的熟练）
			$skill_minimum = 100;
			$skill_limit = 300;
			
			$dice = rand ( - 5, 5 );
			$vefct = NULL;
			if (strpos ( substr($itmk,1), 'V' ) !== false) {//全系技能书
				$skcnt = 0; $ws_sum = 0;
				foreach (array_unique(array_values($skillinfo)) as $key)
				{
					$skcnt++;
					$ws_sum += $$key;
				}
				$vefct = calc_skillbook_efct($itme, $skcnt, $ws_sum);
				foreach (array_unique(array_values($skillinfo)) as $key)
				{
					$$key+=$vefct;
				}
				$wsname = "全系熟练度";
				$useflag = 1;
			} elseif (strpos ( substr($itmk,1), 'P' ) !== false) {
				//保留了一部分六个elseif的原汁原味
				$vefct = calc_skillbook_efct($itme, 1, $wp);
				$wp += $vefct; //$itme;
				$wsname = "斗殴熟练度";
				$useflag = 1;
			} elseif (strpos ( substr($itmk,1), 'K' ) !== false) {
				$vefct = calc_skillbook_efct($itme, 1, $wk);
				$wk += $vefct;
				$wsname = "斩刺熟练度";
				$useflag = 1;
			} elseif (strpos ( substr($itmk,1), 'G' ) !== false) {
				$vefct = calc_skillbook_efct($itme, 1, $wg);
				$wg += $vefct;
				$wsname = "射击熟练度";
				$useflag = 1;
			} elseif (strpos ( substr($itmk,1), 'C' ) !== false) {
				$vefct = calc_skillbook_efct($itme, 1, $wc);
				$wc += $vefct;
				$wsname = "投掷熟练度";
				$useflag = 1;
			} elseif (strpos ( substr($itmk,1), 'D' ) !== false) {
				$vefct = calc_skillbook_efct($itme, 1, $wd);
				$wd += $vefct;
				$wsname = "引爆熟练度";
				$useflag = 1;
			} elseif (strpos ( substr($itmk,1), 'F' ) !== false) {
				$vefct = calc_skillbook_efct($itme, 1, $wf);
				$wf += $vefct;
				$wsname = "灵击熟练度";
				$useflag = 1;
			}
			if(NULL!==$vefct) {
				if ($vefct > 0) {
					$log .= "嗯，有所收获。<br>你的{$wsname}提高了<span class=\"yellow b\">$vefct</span>点！<br>";
				} elseif ($vefct == 0) {
					$log .= "对你来说书里的内容过于简单了。<br>你的熟练度没有任何提升。<br>";
				} else {
					$vefct = - $vefct;
					$log .= "对你来说书里的内容过于简单了。<br>而且由于盲目相信书上的知识，你反而被编写者的纰漏所误导了！<br>你的{$wsname}下降了<span class=\"red b\">$vefct</span>点！<br>";
				}
			}
			if($useflag) \itemmain\itms_reduce($theitem);
			return;
		}
		$chprocess($theitem);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'VOgetcard') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}打开了{$b}，获得了卡片“{$c}”！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
