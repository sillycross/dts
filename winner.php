<?php

define('CURSCRIPT', 'winner');

require './include/common.inc.php';

eval(import_module('player'));

if (isset($_POST['user_prefix'])) $room_prefix=$user_prefix; else $user_prefix = $room_prefix[0];
if (isset($_POST['show_all'])) $showall=$show_all; else $showall=1;
for($i=1;$i<=8;$i++) if(!isset(${'winner_show_wmode_'.$i})) ${'winner_show_wmode_'.$i}=0;
if (!isset($_POST['winner_show_winner'])) $winner_show_winner='';
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
	
	$result = $db->query("SELECT gid FROM {$wtablepre}winners ORDER BY gid DESC LIMIT 1");
	if ($db->num_rows($result)) { $zz=$db->fetch_array($result); $max_gamenum = $zz['gid']; } else $max_gamenum = 0;
	
	//$start预处理
	if(!isset($start) || !$start){
		$start = 0;
	}else{
		$start = (int)$start;
		if($start > $max_gamenum) $start = $max_gamenum;
		elseif($start < $winlimit) $start = $winlimit;
	}
	//生成query
	//gid起始条件（翻页）
	$query_gid = $start > 0 ? "gid<='$start'" : "";
	//wmode条件（胜利类型）
	$query_wmode = ''; $show_wmode_arr = array();
	for($i=1;$i<=8;$i++) {
		if(!empty(${'winner_show_wmode_'.$i})) $show_wmode_arr[] = $i;
	}
	sort($show_wmode_arr);
	if(!empty($show_wmode_arr)) {
		$query_wmode = "wmode IN ('".implode("','",$show_wmode_arr)."')";
	}
	//winner条件（获胜者）
	$query_winner = '';
	if(!empty($winner_show_winner)) {
		$query_winner = "name='$winner_show_winner'";
	}
	//先不拼接gid条件，为了获得所有符合查找条件的结果数
	$query_where = '';
	if(!empty($query_wmode) || !empty($query_winner)) {
		$query_where .= $query_wmode;
		$query_where .= (!empty($query_where) && !empty($query_winner) ? ' AND ' : '') . $query_winner;
		$query_where = ' AND '.$query_where;
	}
	$query_count = "SELECT gid FROM {$wtablepre}winners WHERE gid>0 $query_where ORDER BY gid DESC";
	$result = $db->query($query_count);
	$max_result_num = $db->num_rows($result);
	$max_result_gamenum = 0;
	
	if ($max_result_num) {
		$zz=$db->fetch_array($result);
		$max_result_gamenum = $zz['gid'];
		$db->data_seek($result, $max_result_num - 1);
		$zz=$db->fetch_array($result);
		$min_result_gamenum = $zz['gid'];
	}
	
	//然后拼接含gid的WHERE条件
	$query_where = '';
	if(!empty($query_gid) || !empty($query_wmode) || !empty($query_winner)) {
		$query_where .= $query_gid;
		$query_where .= (!empty($query_where) && !empty($query_wmode) ? ' AND ' : '') . $query_wmode;
		$query_where .= (!empty($query_where) && !empty($query_winner) ? ' AND ' : '') . $query_winner;
		$query_where = ' AND '.$query_where;
	}
	$query_limit = "SELECT gid,gametype,teamID,winnum,namelist,name,icon,gd,wep,wmode,getime,motto,hdp,hdmg,hkp,hkill FROM {$wtablepre}winners WHERE gid>0 $query_where ORDER BY gid DESC LIMIT $winlimit";
	//echo $query;
	$result = $db->query($query_limit);
	
//	if(!isset($start) || !$start){
//		$start = 0;
//		if ($showall==1){
//			$result = $db->query("SELECT gid,gametype,teamID,winnum,namelist,name,icon,gd,wep,wmode,getime,motto,hdp,hdmg,hkp,hkill FROM {$wtablepre}winners ORDER BY gid desc LIMIT $winlimit");
//		}else{
//			$result = $db->query("SELECT gid,gametype,teamID,winnum,namelist,name,icon,gd,wep,wmode,getime,motto,hdp,hdmg,hkp,hkill FROM {$wtablepre}winners WHERE wmode!='1' AND wmode!='4' AND wmode!=6 AND wmode!=8 ORDER BY gid desc LIMIT $winlimit");
//		}
//	} else {
//		$start = (int)$start;
//		if($start > $max_gamenum) $start = $max_gamenum;
//		elseif($start < $winlimit) $start = $winlimit;
//		if ($showall==1){
//			$result = $db->query("SELECT gid,gametype,teamID,winnum,namelist,name,icon,gd,wep,wmode,getime,motto,hdp,hdmg,hkp,hkill FROM {$wtablepre}winners WHERE gid<='$start' ORDER BY gid desc LIMIT $winlimit");
//		}else{//房间优胜记录不显示全部死亡、无人参加、GM中止、挑战结束
//			$result = $db->query("SELECT gid,gametype,teamID,winnum,namelist,name,icon,gd,wep,wmode,getime,motto,hdp,hdmg,hkp,hkill FROM {$wtablepre}winners WHERE gid<='$start' AND wmode!='1' AND wmode!='4' AND wmode!=6 AND wmode!=8 ORDER BY gid desc LIMIT $winlimit");
//		}
//	}
	
	while($wdata = $db->fetch_array($result)) {
		$wdata['date'] = date("Y-m-d",$wdata['getime']);
		$wdata['time'] = date("H:i:s",$wdata['getime']);
		$wdata['iconImg'] = $wdata['gd'] == 'f' ? 'f_'.$wdata['icon'].'.gif' : 'm_'.$wdata['icon'].'.gif';
		$winfo[$wdata['gid']] = $wdata;
	}
	$winfo_keys=array_keys($winfo);rsort($winfo_keys);
	$max_wdata_num=$winfo_keys[0];
	$min_wdata_num = $winfo_keys[sizeof($winfo_keys)-1];
	if($max_result_num > $winlimit){
		if(!isset($start) || !$start) $start = $max_result_gamenum;
		$larger_mark = $smaller_mark = 0;
		$largest_mark = $max_result_gamenum;
		$smallest_mark = max($min_result_gamenum, $winlimit);
		if($start < $largest_mark) {
			$larger_mark = ceil(($start + $winlimit)/$winlimit)*$winlimit;
			if($larger_mark > $largest_mark) $lager_mark = $largest_mark;
		}
		if($start > $smallest_mark) {
			$smaller_mark = ceil(($start - $winlimit)/$winlimit)*$winlimit;
			if($smaller_mark < $smallest_mark) $smaller_mark = $smallest_mark;
			if($smaller_mark > $largest_mark) $smaller_mark = $largest_mark;
		}
	}
	
//	$listnum = floor($max_gamenum/$winlimit);
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