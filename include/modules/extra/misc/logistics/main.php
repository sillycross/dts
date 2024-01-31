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
		$fatenum = crc32($hash);
		$cardid_list = array();
		//固定包括1张S、1张A、1张B
		$cardid_list[1] = $cardindex['S'][$fatenum % count($cardindex['S'])];
		$cardid_list[2] = $cardindex['A'][$fatenum % count($cardindex['A'])];
		$cardid_list[3] = $cardindex['B'][$fatenum % count($cardindex['B'])];
		$arr = array_merge($cardindex['S'],$cardindex['A'],$cardindex['B'],$cardindex['C']);
		$arr = array_diff($arr, $cardid_list);
		
		srand($fatenum);
		$rand_keys = array_rand($arr, 5);
		$i = 4;
		foreach ($rand_keys as $key)
		{
			$cardid_list[$i] = $arr[$key];
			$i += 1;
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
		$cardshop_list[$b10]['blink'] = 10;
		if (isset($b20)) $cardshop_list[$b20]['blink'] = 20;
		return $cardshop_list;
	}
	
	//后勤商店购买道具，暂时只完成了卡片
	//$type为1表示卡片，2表示道具，3表示装饰
	//返回0表示指令错误，返回-1表示购买失败，返回1表示购买成功
	function logistics_buy($itemid, $type, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!in_array($type, array(1,2,3))) return;
			
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
			return 0;//待完成
		}
		elseif ($type == 3)
		{
			return 0;//待完成
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
	
	//使用仓库道具，待完成
	function logistics_useitem($itemid, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
}

?>