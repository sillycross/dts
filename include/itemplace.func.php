<?php
if (! defined ( 'IN_GAME' )) {
	exit ( 'Access Denied' );
}

function get_item_place($which)
{
	if ($which=='-')
		return '-';
	global $plsinfo,$gamecfg;
	//获取某物品的获取方式，如刷新地点或商店是否有卖等
	$result="";
	$file = GAME_ROOT.'./include/modules/base/itemmain/config/mapitem.config.php';
	$itemlist = openfile($file);
	$in = sizeof($itemlist);
	for($i = 1; $i < $in; $i++) {
		if(!empty($itemlist[$i]) && strpos($itemlist[$i],',')!==false)
		{
			list($iarea,$imap,$inum,$iname,$ikind,$ieff,$ista,$iskind) = explode(',',$itemlist[$i]);
			if ($iname==$which)
			{
				if ($iarea==99) $result.="每禁"; else $result.="{$iarea}禁";
				if ($imap==99) $result.="全图随机"; else $result.="于{$plsinfo[$imap]}";
				$result.="刷新{$inum}个<br>";
			}
		}
	}
	$file = GAME_ROOT.'./include/modules/base/itemshop/config/shopitem.config.php';
	$shoplist = openfile($file);
	foreach($shoplist as $lst){
		if(!empty($lst) && strpos($lst,',')!==false)
		{
			list($kind,$num,$price,$area,$item)=explode(',',$lst);
			if ($item==$which)
			{
				$result.="{$area}禁起在商店中出售({$price}元)<br>";
			}
		}
	}
	include_once GAME_ROOT.'./include/modules/base/itemmix/itemmix/config/itemmix.config.php';
	global $mixinfo;
	foreach($mixinfo as $lst){
		if ($lst['result'][0]==$which || $lst['result'][0]==$which.' ')
		{
			$result.="通过合成获取<br>";
			break;
		}
	}
	
	$file = GAME_ROOT.'./include/modules/base/itemmix/itemmix_sync/config/sync.config.php';
	$synlist = openfile($file);
	foreach($synlist as $lst){
		if(!empty($lst) && strpos($lst,',')!==false)
		{
			list($item,$kind)=explode(',',$lst);
			if ($item==$which)
			{
				$result.="通过同调合成获取<br>";
				break;
			}
		}
	}
	$file = GAME_ROOT.'./include/modules/base/itemmix/itemmix_overlay/config/overlay.config.php';
	$ovllist = openfile($file);
	foreach($ovllist as $lst){
		if(!empty($lst) && strpos($lst,',')!==false)
		{
			list($item,$kind)=explode(',',$lst);
			if ($item==$which)
			{
				$result.="通过超量合成获取<br>";
				break;
			}
		}
	}
	$file = GAME_ROOT.'./include/modules/base/items/boxes/config/present.config.php';
	$prslist = openfile($file);
	foreach($prslist as $lst)
		if(!empty($lst) && strpos($lst,',')!==false)
		{
			list($item,$kind)=explode(',',$lst);
			if ($item==$which)
			{
				$result.="打开礼品盒时有概率获得<br>";
				break;
			}
		}
	$file = GAME_ROOT.'./include/modules/base/items/boxes/config/ygobox.config.php';
	$boxlist = openfile($file);
	foreach($boxlist as $lst){
		if(!empty($lst) && strpos($lst,',')!==false)
		{
			list($item,$kind)=explode(',',$lst);
			if ($item==$which)
			{
				$result.="打开游戏王卡包时有概率获得<br>";
				break;
			}
		}
	}
	if ($which=="悲叹之种") $result.="通过使用『灵魂宝石』强化物品失败获得<br>";
	return $result;
}
?>
