<?php

namespace itemshop
{
	function init() {}
	
	function get_shopconfig(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$file = __DIR__.'/config/shopitem.config.php';
		$sl = openfile($file);
		return $sl;
	}
	
	//按类别整理出$sil_bykind
	function get_shopitem_list_by_kind($filecont)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$sil_bykind = array();
		foreach($filecont as $fv){
			if(!empty($fv) && strpos($fv,',')!==false){
				$sil_single = shopitem_row_data_seperate($fv);
				if($sil_single[0]){
					if(!isset($sil_bykind[$sil_single[0]])) $sil_bykind[$sil_single[0]] = array();
					$sil_bykind[$sil_single[0]][] = $sil_single;
				}
			}			
		}
		return $sil_bykind;
	}

	//单行shopitem记录的分割处理
	//本模块是explode后调用shopitem_row_data_process()处理
	function shopitem_row_data_seperate($data){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		return shopitem_row_data_process(explode(',',$data));
	}
	
	//自动合并不同模式的数据，并且给出正确的商店道具数组
	//返回不含类别的$sil
	function get_shopitem_list($filecont=array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//首先是基础数据
		$file = __DIR__.'/config/shopitem.config.php';
		$filecont0 = openfile($file);
		$sil_bykind = get_shopitem_list_by_kind($filecont0);
		
		//其次是不同模式的定制数据
		if(!empty($filecont) && $filecont != $filecont0){
			//gwrite_var('a.txt',array_diff($filecont0, $filecont));
			$sil_bykind_c = get_shopitem_list_by_kind($filecont);
			foreach($sil_bykind_c as $sk => $sv){
				$sil_bykind[$sk] = $sv;
			}
		}
		
		ksort($sil_bykind);
		$sil = array();
		//然后去掉类别，返回一个扁平化的列表
		foreach($sil_bykind as $sv) {
			foreach($sv as $v){
//				$tmp = array();
//				list($tmp['kind'], $tmp['num'], $tmp['price'], $tmp['area'], $tmp['item'], $tmp['itmk'], $tmp['itme'], $tmp['itms'], $tmp['itmsk']) = list($v);
				$sil[] = $v;
			}
		}
		return $sil;
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
			
			$shopitem_list = get_shopitem_list(get_shopconfig());
			
			$qry = '';
			foreach($shopitem_list as $lst){
				list($kind,$num,$price,$area,$item,$itmk,$itme,$itms,$itmsk)=$lst;
				$qry .= "('$kind','$num','$price','$area','$item','$itmk','$itme','$itms','$itmsk'),";	
			}
			if(!empty($qry)){
				$qry = "INSERT INTO {$tablepre}shopitem (kind,num,price,area,item,itmk,itme,itms,itmsk) VALUES ".substr($qry, 0, -1);
			}
			$db->query($qry);
		}
	}
	
	//单条数据处理，用于某些特殊模式
	function shopitem_row_data_process($data){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		return $data;
	}
	
	function prepare_shopitem($sn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map','itemmain'));
		$arean = \map\get_area_wavenum();
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
		return MOD_ITEMSHOP_SHOP;
	}
	
	function get_sp_shop_filename(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
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
		} elseif( ($shopiteminfo['itms']==='∞' || !preg_match('/^(WC|WD|WF|Y|B|C|TN|GA|GB|H|P|V|M|X|p|ygo|EA)/',$shopiteminfo['itmk']) )&&$bnum>1) {
			$log .= '此物品一次只能购买一个。<br><br>';
			$mode = 'command';
			return;
		}elseif($shopiteminfo['area'] > \map\get_area_wavenum()){
			$log .= '此物品尚未开放出售！<br><br>';
			$mode = 'command';
			return;
		}

		$inum = $shopiteminfo['num']-$bnum;
		$sid = $shopiteminfo['sid'];
		$db->query("UPDATE {$tablepre}shopitem SET num = '$inum' WHERE sid = '$sid'");

		$money -= $cost;
		//原设定是会发一条隐藏的进行状况的
		//addnews($now,'itembuy',$name,$shopiteminfo['item']);
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
		
		eval(import_module('sys','player','logger','itemmain','itemshop'));
		$sp_cmd = get_var_input('sp_cmd');
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
				} 
				elseif($command == 'shop') {	//返回初级页面
					ob_clean();
					include template(get_sp_shop_filename());
					$cmd = ob_get_contents();
					ob_clean();
				} else {
					list($shoptype,$buynum) = get_var_input('shoptype','buynum');
					itembuy($command,$shoptype,$buynum);
				}
			}else{
				$log .= '<span class="yellow b">你所在的地区没有商店。</span><br />';
				$mode = 'command';
			}
			return;
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($news == 'itembuy') {
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}购买了{$b}</span></li>";
		}
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>