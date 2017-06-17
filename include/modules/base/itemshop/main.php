<?php

namespace itemshop
{
	function init() {}
	
	function get_shoplist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		$file = __DIR__.'/config/shopitem.config.php';
		$sl = openfile($file);
		return $sl;
	}
	
	function rs_game($xmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		
		$chprocess($xmode);
		
		$sqldir = GAME_ROOT.'./gamedata/sql/';
		
		eval(import_module('sys'));
		if ($xmode & 32) {
			//echo " - 商店初始化 - ";
			$sql = file_get_contents("{$sqldir}shopitem.sql");
			$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
			$db->queries($sql);
			
			$shoplist=get_shoplist();
			
			$qry = '';
			foreach($shoplist as $lst){
				if(!empty($lst) && strpos($lst,',')!==false){
					list($kind,$num,$price,$area,$item,$itmk,$itme,$itms,$itmsk)=explode(',',$lst);
					if($kind != 0){
						$qry .= "('$kind','$num','$price','$area','$item','$itmk','$itme','$itms','$itmsk'),";
					}
				}			
			}
			if(!empty($qry)){
				$qry = "INSERT INTO {$tablepre}shopitem (kind,num,price,area,item,itmk,itme,itms,itmsk) VALUES ".substr($qry, 0, -1);
			}
			$db->query($qry);
		}
	}
	
	function prepare_shopitem($sn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map','itemmain'));
		$arean = floor($areanum / $areaadd); 
		$result=$db->query("SELECT * FROM {$tablepre}shopitem WHERE kind = '$sn' AND area <= '$arean' AND num > '0' AND price > '0' ORDER BY sid");
		$shopnum = $db->num_rows($result);
		for($i=0;$i< $shopnum;$i++){
			$itemdata[$i]=$db->fetch_array($result);
			$itemdata[$i]['itmk_words']=\itemmain\parse_itmk_words($itemdata[$i]['itmk'],1);
			$itemdata[$i]['itmsk_words']=\itemmain\parse_itmsk_words($itemdata[$i]['itmsk'],1);
		}
		return $itemdata;
	}
	
	function shoplist($sn) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		$itemdata = prepare_shopitem($sn);
		
		$shop=$sn;
		ob_clean();
		include template(get_itemshop_filename());
		$cmd = ob_get_contents();
		ob_clean();
		return;
	}
	
	function get_itemshop_filename(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		return MOD_ITEMSHOP_SHOP;
	}
	
	function get_sp_shop_filename(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		return MOD_ITEMSHOP_SP_SHOP;
	}

	function get_shopiteminfo($item)
	{	
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		$result=$db->query("SELECT * FROM {$tablepre}shopitem WHERE sid = '$item'");
		$shopiteminfo = $db->fetch_array($result);
		return $shopiteminfo;
	}
	
	function calculate_shop_itembuy_cost($price,$bnum)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $price*$bnum;
	}
	
	function itembuy($item,$shop,$bnum=1) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map','player','logger','itemmain'));
		$shopiteminfo = get_shopiteminfo($item);
		$price = $shopiteminfo['price'];
		
		if($price<0) {
			$log .= '要购买的道具不存在！<br><br>';
			$mode = 'command';
			return;
		}

		$bnum = (int)$bnum;
		$cost=calculate_shop_itembuy_cost($price,$bnum);
		
		//list($num,$price,$iname,$ikind,$ieff,$ista,$isk) = explode(',',$iteminfo);
		if($shopiteminfo['num'] <= 0) {
			$log .= '此物品已经售空！<br><br>';
			$mode = 'command';
			return;
		} elseif($bnum<=0) {
			$log .= '购买数量必须为大于0的整数。<br><br>';
			$mode = 'command';
			return;
		} elseif($bnum>$shopiteminfo['num']) {
			$log .= '购买数量必须小于存货数量。<br><br>';
			$mode = 'command';
			return;
		} elseif($money < $cost) {
			$log .= '你的钱不够，不能购买此物品！<br><br>';
			$mode = 'command';
			return;
		} elseif( ($shopiteminfo['itms']==='∞' || !preg_match('/^(WC|WD|WF|Y|B|C|TN|GB|H|P|V|M|X|p|ygo)/',$shopiteminfo['itmk']) )&&$bnum>1) {
			$log .= '此物品一次只能购买一个。<br><br>';
			$mode = 'command';
			return;
		}elseif($shopiteminfo['area']> $areanum/$areaadd){
			$log .= '此物品尚未开放出售！<br><br>';
			$mode = 'command';
			return;
		}

		$inum = $shopiteminfo['num']-$bnum;
		$sid = $shopiteminfo['sid'];
		$db->query("UPDATE {$tablepre}shopitem SET num = '$inum' WHERE sid = '$sid'");

		$money -= $cost;
	
		addnews($now,'itembuy',$name,$iteminfo['item']);
		$log .= "购买成功。";
		$itm0 = $shopiteminfo['item'];
		$itmk0 = $shopiteminfo['itmk'];
		$itme0 = $shopiteminfo['itme'];
		$itms0 = $shopiteminfo['itms'] === '∞' ? $shopiteminfo['itms'] : $shopiteminfo['itms']*$bnum;
		$itmsk0 = $shopiteminfo['itmsk'];

		\itemmain\itemget();	
		return;
	}
	
	function check_in_shop_area($p)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('itemshop'));
		return in_array($p,$shops);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger','itemmain','itemshop','input'));
		if ($mode == 'command' && $command == 'special' && $sp_cmd == 'sp_shop')	//进入初级页面
		{
			ob_clean();
			include template(get_sp_shop_filename());
			$cmd = ob_get_contents();
			ob_clean();
			return;
		}
		
		if($mode == 'special' && strpos($command,'shop') === 0)	//进入次级页面
		{
			$shop = substr($command,4,2);
			shoplist($shop);
			return;
		}
		
		if($mode == 'shop') 	//次级shop页面的操作
		{
			if(check_in_shop_area($pls)){
				if ($command == 'menu') {	//离开商店
					$mode = 'command';
					return;
				} 
				else if($command == 'shop') {	//返回初级页面
					ob_clean();
					include template(get_sp_shop_filename());
					$cmd = ob_get_contents();
					ob_clean();
					return;
				} else {
					itembuy($command,$shoptype,$buynum);
					return;
				}
			}else{
				$log .= '<span class="yellow">你所在的地区没有商店。</span><br />';
				$mode = 'command';
				return;
			}
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($news == 'itembuy') {
			//return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}购买了{$b}</span></li>";
			return '';
		}
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
		
}

?>
