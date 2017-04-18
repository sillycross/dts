<?php

define('CURSCRIPT', 'winner');

require './include/common.inc.php';

eval(import_module('player'));

if (isset($_POST['user_prefix'])) $room_prefix=$user_prefix; else $user_prefix = $room_prefix[0];
if (isset($_POST['show_all'])) $showall=$show_all; else $showall=1;
$room_gprefix = '';
if ($room_prefix!='') $room_gprefix = ((string)$room_prefix).'.';
if ($room_gprefix!='') $wtablepre = $gtablepre . $room_gprefix[0]; else $wtablepre = $gtablepre;

if(!isset($command)){$command = 'ref';}
if($command == 'info') {
	$result = $db->query("SELECT * FROM {$wtablepre}winners WHERE gid='$gnum' LIMIT 1");
	$pdata = $db->fetch_array($result);
	$pdata['gdate'] = floor($pdata['gtime']/3600).':'.floor($pdata['gtime']%3600/60).':'.($pdata['gtime']%60);
	$pdata['gsdate'] = date("m/d/Y H:i:s",$pdata['gstime']);
	$pdata['gedate'] = date("m/d/Y H:i:s",$pdata['getime']);
	\player\load_playerdata($pdata);
	\player\init_playerdata();
	\player\init_profile();
	extract($pdata);
} elseif($command == 'news') {
	$hnewsfile = GAME_ROOT."./gamedata/bak/{$room_gprefix}{$gnum}_newsinfo.html";
	if(file_exists($hnewsfile)){
		$hnewsinfo = readover($hnewsfile);
	}
} else {
	if(!isset($start) || !$start){
		$start = 0;
		if ($showall==1){
			$result = $db->query("SELECT gid,gametype,teamID,winnum,namelist,name,icon,gd,wep,wmode,getime,motto,hdp,hdmg,hkp,hkill FROM {$wtablepre}winners ORDER BY gid desc LIMIT $winlimit");
		}else{
			$result = $db->query("SELECT gid,gametype,teamID,winnum,namelist,name,icon,gd,wep,wmode,getime,motto,hdp,hdmg,hkp,hkill FROM {$wtablepre}winners WHERE wmode!='1' ORDER BY gid desc LIMIT $winlimit");
		}
	} else {
		if ($showall==1){
			$result = $db->query("SELECT gid,gametype,teamID,winnum,namelist,name,icon,gd,wep,wmode,getime,motto,hdp,hdmg,hkp,hkill FROM {$wtablepre}winners WHERE gid<='$start' ORDER BY gid desc LIMIT $winlimit");
		}else{
			$result = $db->query("SELECT gid,gametype,teamID,winnum,namelist,name,icon,gd,wep,wmode,getime,motto,hdp,hdmg,hkp,hkill FROM {$wtablepre}winners WHERE gid<='$start' AND wmode!='1' ORDER BY gid desc LIMIT $winlimit");
		}
	}
	while($wdata = $db->fetch_array($result)) {
		$wdata['date'] = date("Y-m-d",$wdata['getime']);
		$wdata['time'] = date("H:i:s",$wdata['getime']);
		$wdata['iconImg'] = $wdata['gd'] == 'f' ? 'f_'.$wdata['icon'].'.gif' : 'm_'.$wdata['icon'].'.gif';
		$winfo[$wdata['gid']] = $wdata;
	}
	
	$result = $db->query("SELECT gid FROM {$wtablepre}winners ORDER BY gid DESC LIMIT 1");
	if ($db->num_rows($result)) { $zz=$db->fetch_array($result); $mgamenum = $zz['gid']; } else $mgamenum = 0;
	
	//计算书签
	//$cmark
	$max_mark_count= (int)ceil($mgamenum/$winlimit);
//	if($cmark > $max_mark_count) $cmark = $max_mark_count;
//	elseif($cmark < 1) $cmark = 1;
	//页码大于1才显示书签
	if($max_mark_count > 1){
		if(!isset($start) || !$start) $start = $mgamenum;
		if($start > $mgamenum) $start = $mgamenum;
		elseif($start < $winlimit) $start = $winlimit;
		$larger_mark = $smaller_mark = 0;
		$largest_mark = $mgamenum;
		$smallest_mark = $winlimit;
		if($start < $largest_mark) {
			$larger_mark = $start + $winlimit;
			if($larger_mark > $largest_mark) $lager_mark = $largest_mark;
		}
		if($start > $smallest_mark) {
			$smaller_mark = $start - $winlimit;
			if($smaller_mark < $smallest_mark) $smaller_mark = $smallest_mark;
		}
		if($pagelimit <= 0) $pagelimit = 1;
		$markarr = array($start);
		for($i=0;$i<=(int)$pagelimit;$i++){
			$lmark = $start + $winlimit * $i;
			$smark = $start - $winlimit * $i;
			if($lmark < $largest_mark && !in_array($lmark,$markarr)) $markarr[] = $lmark;
			if($smark > $smallest_mark && !in_array($smark,$markarr)) $markarr[] = $smark;
			if(sizeof($markarr) >= $pagelimit) break;
		}
		//sort($markarr);
	}
	
//	$listnum = floor($mgamenum/$winlimit);
//
//	for($i=0;$i<$listnum;$i++) {
//		$snum = ($listnum-$i)*$winlimit;
//		$enum = $snum-$winlimit+1;
//		$listinfo .= "<input style='width: 120px;' type='button' value='{$snum} ~ {$enum} 回' onClick=\"document['list']['start'].value = '$snum'; document['list'].submit();\">";
//		if(is_int(($i+1)/3)&&$i){$listinfo .= '<br>';}
//	}
	
	if ($command=='replay')
	{
		$result = $db->query("SELECT wmode FROM {$wtablepre}winners where gid='$gnum'");
		if ($db->num_rows($result))
		{
			$zz = $db->fetch_array($result);
			$rep_winmode = $zz['wmode'];
		}
		else  $rep_winmode = 4;
	}
}

include template('winner');

?>