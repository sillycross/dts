<?php

namespace logistics
{
	function init() {
		
	}
	
	//获取商店卡片列表
	function get_cardshop_list($udata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		$uid = $udata['uid'];
		$uname = $udata['username'];
		list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));
		$hash = md5($uid.$uname.$day.$month.$year.$wday);
		$fatenum = hexdec(substr($hash, 0, 5).substr($hash, -5));
		$cardid_list = array();
		//固定包括1张S、1张A、1张B
		$cardid_list[1] = $cardindex['S'][$fatenum % count($cardindex['S'])];
		$cardid_list[2] = $cardindex['A'][$fatenum % count($cardindex['A'])];
		$cardid_list[3] = $cardindex['B'][$fatenum % count($cardindex['B'])];
		$arr = array_merge($cardindex['S'],$cardindex['A'],$cardindex['B'],$cardindex['C']);
		$arr = array_diff($arr, $cardid_list);
		
		$count_arr = count($arr);
		$magic_arr = Array(11,101,233,571,1997);
		for($i=4;$i<=8;$i++){
			$cardid_list[$i] = $arr[round($fatenum / $magic_arr[$i-4]) % count($arr)];
		}
		
		$cardshop_list = array();
		foreach ($cardid_list as $k=>$v)
		{
			$cardshop_list[$k] = $cards[$v];
			$cardshop_list[$k]['id'] = $v;
			$cardshop_list[$k]['blink'] = 0;
		}
		//生成随机碎闪
		$b10 = $fatenum % 10 + 1;
		if ($b10 > 8)
		{
			$b10 = $fatenum % 8 + 1;
			$b20 = $fatenum % 3 + 1;
		}
		if ($cardshop_list[$b10]['rare'] != 'M') $cardshop_list[$b10]['blink'] = 10;
		if (isset($b20)) $cardshop_list[$b20]['blink'] = 20;
		return $cardshop_list;
	}
	
	//后勤商店购买道具，暂时只完成了卡片
	//$type为1表示卡片，2表示道具
	//返回0表示指令错误，返回-1表示购买失败，返回1表示购买成功
	function logistics_buy($itemid, $type, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!in_array($type, array(1,2))) return 0;
		
		if ($type == 1)
		{
			$cardshop_list = get_cardshop_list($pa);
			if (!isset($cardshop_list[$itemid])) return 0;
			$nowcard = $cardshop_list[$itemid];
			
			$cost = get_card_price($nowcard);
			if (empty($cost)) return 0;
			if ($pa['gold'] < $cost) return -1;
			$card_data = \cardbase\get_cardlist_energy_from_udata($pa)[2];
			$o_blink = !empty($card_data[$nowcard['id']]['blink']) ? $card_data[$nowcard['id']]['blink'] : 0;
			if ($o_blink >= $nowcard['blink']) return -1;
			
			if (!empty($pa)) \cardbase\get_qiegao(-$cost,$pa);
			
			if (isset($nowcard['blink'])) $blink = $nowcard['blink'];
			else $blink = 0;
			\cardbase\get_card_alternative($nowcard['id'], $pa, 0, $blink);
			
			if($pa){
				$un = $pa['username'];
				$upd = Array(
					'gold' => $pa['gold'],
					'card_data' => $pa['card_data'],
				);
				update_udata_by_username($upd, $un);
			}
		}
		elseif ($type == 2)
		{
			eval(import_module('logistics'));
			if (!isset($logistics_shop_items[$itemid])) return 0;
			if ($logistics_shop_items[$itemid][4]) return 0;
			$cost = $logistics_shop_items[$itemid][2];
			if ($cost <= 0) return -1;
			if ($pa['gold'] < $cost) return -1;
			
			if (!empty($pa)) \cardbase\get_qiegao(-$cost,$pa);
			
			logistics_itemget($itemid, $pa, 1);
			
			if($pa){
				$un = $pa['username'];
				$upd = Array(
					'gold' => $pa['gold'],
					'u_settings' => $pa['u_settings'],
				);
				update_udata_by_username($upd, $un);
			}
		}
		return 1;
	}
	
	//根据卡片稀有度和闪/碎计算售价
	function get_card_price($nowcard)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logistics'));
		if (isset($cardtype_sellprice[$nowcard['rare']])) $price = $cardtype_sellprice[$nowcard['rare']];
		else $price = 233;
		if (isset($nowcard['blink']) && isset($card_sellprice_blink_rate[$nowcard['blink']])) $price *= $card_sellprice_blink_rate[$nowcard['blink']];
		return $price;
	}
	
	//获取持有道具列表
	//以道具id为键名，键值为数量
	function logistics_get_itemlist_from_udata($udata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$u_settings = \user_settings\get_u_settings($udata);
		if (isset($u_settings['log_itemlist'])) $ret = $u_settings['log_itemlist'];
		else $ret = array();
		return $ret;
	}
	
	//储存持有道具列表
	function logistics_put_itemlist_to_udata(&$itemlist, &$udata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$u_settings = \user_settings\get_u_settings($udata);
		$u_settings['log_itemlist'] = $itemlist;
		$udata['u_settings'] = gencode($u_settings);
		return $udata;
	}
	
	//获得仓库道具
	function logistics_itemget($itemid, &$pa, $num = 1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$itemlist = logistics_get_itemlist_from_udata($pa);
		if (isset($itemlist[$itemid])) $itemlist[$itemid] += $num;
		else $itemlist[$itemid] = $num;
		$pa = logistics_put_itemlist_to_udata($itemlist, $pa);
		return 1;
	}
	
	//使用仓库道具
	//使用成功返回使用结果的字符串，返回0表示指令错误或使用失败
	function logistics_itemuse($itemid, $para, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!$pa) return 0;
		eval(import_module('logistics'));
		if (!isset($logistics_shop_items[$itemid])) return 0;
		
		$itemlist = logistics_get_itemlist_from_udata($pa);
		if ($itemlist[$itemid] <= 0) return 0;
		
		$upd = array();
		$log = '';
		switch ($itemid)
		{
			case 1://切糕盒，使用后随机获得1-200切糕
				$qiegaogain = rand(1,200);
				\cardbase\get_qiegao($qiegaogain, $pa);
				$log .= "使用了切糕盒子，获得了<span class=\"yellow b\">{$qiegaogain}</span>切糕。<br>";
				logistics_itemget($itemid, $pa, -1);
				
				$upd['gold'] = $pa['gold'];
				break;
			case 2://消耗切糕，使一张卡片变为闪烁
				$card_data = \cardbase\get_cardlist_energy_from_udata($pa)[2];
				if (!isset($card_data[$para])) return 0;
				eval(import_module('cardbase'));
				if ($cards[$para]['rare'] == 'M') return 0;
				$o_blink = !empty($card_data[$para]['blink']) ? $card_data[$para]['blink'] : 0;
				if ($o_blink >= 10) return 0;
				
				$qiegaocost = $cardblink_upgrade_cost[$cards[$para]['rare']][0];
				if ($qiegaocost <= 0 || ($pa['gold'] < $qiegaocost)) return 0;
				
				\cardbase\get_qiegao(-$qiegaocost, $pa);
				$rarecolor = $card_rarecolor[$cards[$para]['rare']];
				$log .= "消耗<span class=\"yellow b\">{$qiegaocost}</span>切糕，使卡片<span class=\"{$rarecolor} b\">【{$cards[$para]['name']}】</span>变为了闪烁。<br>";
				
				\cardbase\get_card_alternative($para, $pa, 0, 10);
				logistics_itemget($itemid, $pa, -1);
				
				$upd['gold'] = $pa['gold'];
				$upd['card_data'] = $pa['card_data'];
				break;
			case 3://消耗切糕，使一张卡片变为镜碎
				$card_data = \cardbase\get_cardlist_energy_from_udata($pa)[2];
				if (!isset($card_data[$para])) return 0;
				eval(import_module('cardbase'));
				if (($cards[$para]['rare'] == 'C') || ($cards[$para]['rare'] == 'M')) return 0;
				$o_blink = !empty($card_data[$para]['blink']) ? $card_data[$para]['blink'] : 0;
				if ($o_blink >= 20) return 0;
				
				$qiegaocost = $cardblink_upgrade_cost[$cards[$para]['rare']][1];
				if ($qiegaocost <= 0 || ($pa['gold'] < $qiegaocost)) return 0;
				
				\cardbase\get_qiegao(-$qiegaocost, $pa);
				$rarecolor = $card_rarecolor[$cards[$para]['rare']];
				$log .= "消耗<span class=\"yellow b\">{$qiegaocost}</span>切糕，使卡片<span class=\"{$rarecolor} b\">【{$cards[$para]['name']}】</span>变为了镜碎。<br>";
				
				\cardbase\get_card_alternative($para, $pa, 0, 20);
				logistics_itemget($itemid, $pa, -1);
				
				$upd['gold'] = $pa['gold'];
				$upd['card_data'] = $pa['card_data'];
				break;
			case 4://能量饮料，选一张卡片充能
				list($cardlist, $cardenergy, $card_data) = \cardbase\get_cardlist_energy_from_udata($pa);
				if (!isset($card_data[$para])) return 0;
				
				eval(import_module('cardbase'));
				$cardenergy[$para] = $cards[$para]['energy'];
				$rarecolor = $card_rarecolor[$cards[$para]['rare']];
				$log .= "卡片<span class=\"{$rarecolor} b\">【{$cards[$para]['name']}】</span>完成充能了。<br>";
				
				\cardbase\put_cardlist_energy_to_udata($cardlist, $cardenergy, $card_data, $pa);
				logistics_itemget($itemid, $pa, -1);
				
				$upd['card_data'] = $pa['card_data'];
				break;
			default:
				//装饰品
				if ($logistics_shop_items[$itemid][1] == 2)
				{
					$ret = set_showcase_logitem($itemid, $para, $pa);
					if ($ret)
					{
						$log .= "你将<span class=\"yellow b\">{$logistics_shop_items[$itemid][0]}</span>摆到了展柜中。<br>";
					}
				}
				break;
		}
		
		if ($log)
		{
			$un = $pa['username'];
			$upd['u_settings'] = $pa['u_settings'];
			update_udata_by_username($upd, $un);
		}
		
		return $log;
	}
	
	//获取展柜卡片列表
	function get_showcase_cardlist_from_udata($udata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$u_settings = \user_settings\get_u_settings($udata);
		if (isset($u_settings['log_s_cardlist'])) $ret = $u_settings['log_s_cardlist'];
		else $ret = array(0,0,0);
		if (count($ret) != 3) $ret = array(0,0,0);
		return $ret;
	}
	
	//储存展柜卡片列表
	function put_showcase_cardlist_to_udata(&$s_cardlist, &$udata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$u_settings = \user_settings\get_u_settings($udata);
		$u_settings['log_s_cardlist'] = $s_cardlist;
		$udata['u_settings'] = gencode($u_settings);
		return $udata;
	}
	
	//设置展柜卡牌
	function set_showcase_card($cardchoice, $cardpos, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$cardchoice = (int)$cardchoice;
		$cardpos = (int)$cardpos;
		
		if (!in_array($cardpos, array(1,2,3))) return 0;
		
		$cardlist = \cardbase\get_cardlist_energy_from_udata($pa)[0];
		if (!in_array($cardchoice, $cardlist)) return 0;
		
		$s_cardlist = get_showcase_cardlist_from_udata($pa);
		if (in_array($cardchoice, $s_cardlist)) return 0;
		$s_cardlist[$cardpos-1] = $cardchoice;
		
		put_showcase_cardlist_to_udata($s_cardlist, $pa);
		
		$upd = array();
		$un = $pa['username'];
		$upd['u_settings'] = $pa['u_settings'];
		update_udata_by_username($upd, $un);
		
		return 1;
	}
	
	//获取展柜游戏道具列表
	function get_showcase_gameitemlist_from_udata($udata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$u_settings = \user_settings\get_u_settings($udata);
		if (isset($u_settings['log_s_gameitemlist'])) $ret = $u_settings['log_s_gameitemlist'];
		else $ret = array();
		return $ret;
	}
	
	//储存持有道具列表
	function put_showcase_gameitemlist_to_udata(&$s_gameitemlist, &$udata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$u_settings = \user_settings\get_u_settings($udata);
		$u_settings['log_s_gameitemlist'] = $s_gameitemlist;
		$udata['u_settings'] = gencode($u_settings);
		return $udata;
	}
	
	//记录道具
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if(strpos($k,'Y')===0 || strpos($k,'Z')===0){
			if ($n == '记录用投影底座'){
				$ret .= '可以选择一个包裹中的道具，将它的投影记录到展柜中';
			}
		}
		return $ret;
	}
	
	//设置展柜游戏道具
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos($itmk, 'Y') === 0 || strpos($itmk, 'Z') === 0)
		{	
			if ($itm == '记录用投影底座') {
				include template(MOD_LOGISTICS_USE_GAMEITEM_RECORDER);
				$cmd = ob_get_contents();
				ob_clean();
				$log .= "使用了<span class=\"yellow b\">$itm</span>。<br>";
				return;
			} 
		}
		$chprocess($theitem);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$usemode = get_var_input('usemode');
		if ($mode == 'item' && $usemode == 'gameitemrecorder') 
		{
			$itmn = (int)substr($command, 3);
			$itmp = (int)get_var_input('itmp');
			$pos = (int)get_var_input('pos');
			set_showcase_gameitem($itmp, $itmn, $pos);
			return;
		}
		$chprocess();
	}
	
	function set_showcase_gameitem($itmp, $itmn, $pos)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if ($pos < 1 || $pos > 4)
		{
			$log .= '输入参数错误。';
			$mode = 'command';
			return;
		}
		
		if ($itmp < 1 || $itmp > 6 || $itmn < 1 || $itmn > 6 || $itmn == $itmp)
		{
			$log .= '道具选择错误，请重新选择。<br>';
			$mode = 'command';
			return;
		}
		
		$rec = & ${'itm'.$itmp};
		$recs = & ${'itms'.$itmp};
		
		if (!$recs || ('记录用投影底座' !== $rec))
		{
			$log .= '道具选择错误，请重新选择。<br>';
			$mode = 'command';
			return;
		}
		
		$itm = & ${'itm'.$itmn};
		$itme = & ${'itme'.$itmn};
		$itms = & ${'itms'.$itmn};
		$itmk = & ${'itmk'.$itmn};
		$itmsk = & ${'itmsk'.$itmn};
		
		if (!$itms)
		{
			$log .= '道具选择错误，请重新选择。<br>';
			$mode = 'command';
			return;
		}
		elseif (strlen($itmsk > 1024))
		{
			$log .= '记录仪试图记录这个道具，然而失败了。<br>';
			$mode = 'command';
			return;
		}
		
		$theitem = array($itm, $itmk, $itme, $itms, $itmsk);
		
		$udata = fetch_udata_by_username($name);
		$s_gameitemlist = get_showcase_gameitemlist_from_udata($udata);
		$s_gameitemlist[$pos] = $theitem;
		put_showcase_gameitemlist_to_udata($s_gameitemlist, $udata);
		
		$upd = array();
		$un = $udata['username'];
		$upd['u_settings'] = $udata['u_settings'];
		update_udata_by_username($upd, $un);
		
		$log .= "你将<span class=\"yellow b\">{$itm}</span>的投影保存到了装置中。<br>";

		$mode = 'command';
		return;
	}
	
	//获取展柜装饰品列表
	function get_showcase_logitemlist_from_udata($udata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$u_settings = \user_settings\get_u_settings($udata);
		if (isset($u_settings['log_s_logitemlist'])) $ret = $u_settings['log_s_logitemlist'];
		else $ret = array();
		return $ret;
	}
	
	//储存展柜装饰品列表
	function put_showcase_logitemlist_to_udata(&$s_logitemlist, &$udata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$u_settings = \user_settings\get_u_settings($udata);
		$u_settings['log_s_logitemlist'] = $s_logitemlist;
		$udata['u_settings'] = gencode($u_settings);
		return $udata;
	}
	
	//设置展柜装饰品
	function set_showcase_logitem($itemid, $itempos, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$itemid = (int)$itemid;
		$itempos = (int)$itempos;
		
		if (!in_array($itempos, array(1,2,3,4,5))) return 0;
		
		$itemlist = logistics_get_itemlist_from_udata($pa);
		if ($itemlist[$itemid] <= 0) return 0;
		
		$s_logitemlist = get_showcase_logitemlist_from_udata($pa);
		if (empty($s_logitemlist) || (count($s_logitemlist) != 5)) $s_logitemlist = array(0,0,0,0,0);
		
		if (in_array($itemid, $s_logitemlist)) return 0;
		$s_logitemlist[$itempos-1] = $itemid;
		
		put_showcase_logitemlist_to_udata($s_logitemlist, $pa);
		
		$upd = array();
		$un = $pa['username'];
		$upd['u_settings'] = $pa['u_settings'];
		update_udata_by_username($upd, $un);
		
		return 1;
	}
	
}

?>