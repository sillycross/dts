<?php
if(!defined('IN_GAME')) {
	exit('Access Denied');
}

eval(import_module('player','cardbase'));

if (isset($user_prefix)) {
	$room_prefix=$user_prefix;
}else {
	$user_prefix = room_prefix_kind($room_prefix);
}
if (isset($show_all)) $showall=$show_all; else $showall=1;
for($i=1;$i<=8;$i++) if(!isset(${'winner_show_wmode_'.$i})) ${'winner_show_wmode_'.$i}=0;
for($i=0;$i<=20;$i++) if(!isset(${'winner_show_gtype_'.$i})) ${'winner_show_gtype_'.$i}=0;
if (!isset($winner_show_winner)) $winner_show_winner='';
$room_gprefix = '';
if (room_check_subroom($room_prefix)) $room_gprefix = ((string)$room_prefix).'.';
if ($room_gprefix!='') $wtablepre = $gtablepre . $room_gprefix[0]; else $wtablepre = $gtablepre;

//兼容代码，判定是不是存在旧版winner表
//$winner_table_exists = 0;
//$result = $db->query("SHOW TABLES LIKE '{$wtablepre}winners';");
//if ($db->num_rows($result)) $winner_table_exists = 1;

if(!isset($command)){$command = 'ref';}

if(!function_exists('winner_parse')){
	function winner_parse($wdata){
		$wdata['winnerpdata'] = gdecode($wdata['winnerpdata'],1);
		$wdata['validlist'] = gdecode($wdata['validlist'],1);
		foreach($wdata['winnerpdata'] as $wdk => $wdv){
			$wdata[$wdk] = $wdv;
		}
		unset($wdata['winnerpdata']);
		if(empty($wdata['gd'])) $wdata['gd']='m';
		if(empty($wdata['icon'])) $wdata['icon']=0;
		if(empty($wdata['wep'])) $wdata['wep']='';
		return $wdata;
	}
}

//查看特定获胜者的资料
if($command == 'info') 
{
	$result = $db->query("SELECT * FROM {$wtablepre}history WHERE gid='$gnum' LIMIT 1");
	if($db->num_rows($result)) {
		$wdata = $db->fetch_array($result);
		$wdata = winner_parse($wdata);
		$wdata['gdate'] = floor($wdata['gtime']/3600).':'.floor($wdata['gtime']%3600/60).':'.($wdata['gtime']%60);
		$wdata['gsdate'] = date("m/d/Y H:i:s",$wdata['gstime']);
		$wdata['gedate'] = date("m/d/Y H:i:s",$wdata['getime']);
		$wdata['duration'] = !empty($wdata['validtime']) ? $wdata['getime']-$wdata['validtime'] : $wdata['getime'] - $wdata['gstime'];
	}
	$pdata = $wdata;
	\player\load_playerdata($pdata);
	\player\init_playerdata();
	\player\parse_interface_profile();
	extract($pdata);
	list($vapm,$aapm) =  \apm\calc_winner_apm($wdata,$wdata['duration']);
}
//查看特定局历史记录
elseif($command == 'news') 
{
	$hnewsinfo = '';
	$result = $db->query("SELECT hnews FROM {$wtablepre}history WHERE gid='$gnum' LIMIT 1");
	if($db->num_rows($result)) {
		$whd = $db->fetch_array($result);
		if($whd['hnews'])
			$hnewsinfo = gdecode($whd['hnews'],1);
	}
	if(empty($hnewsinfo)){//兼容代码
		$hnewsfile1 = GAME_ROOT."./gamedata/bak/{$room_gprefix}{$gnum}_newsinfo.dat";
		$hnewsfile2 = GAME_ROOT."./gamedata/bak/{$room_gprefix}{$gnum}_newsinfo.html";
		if(file_exists($hnewsfile1)){
			$hnewsinfo = readover($hnewsfile1);
			$hnewsinfo = gdecode($hnewsinfo,1);
		}elseif(file_exists($hnewsfile2)){
			$hnewsinfo = readover($hnewsfile2);
		}
	}	
}
//其他情况都认为是看列表
else 
{
//	$max_gid1 = $max_gid2 = 0;
	$max_gamenum = 0;
	$result = $db->query("SELECT gid FROM {$wtablepre}history ORDER BY gid DESC LIMIT 1");
	if ($db->num_rows($result)) {
		 $tmp_rst=$db->fetch_array($result); 
		 $max_gamenum = $tmp_rst['gid']; 
	}
//	if ($winner_table_exists) {
//		$result = $db->query("SELECT gid FROM {$wtablepre}winners ORDER BY gid DESC LIMIT 1");
//		if ($db->num_rows($result)) {
//			 $tmp_rst=$db->fetch_array($result); 
//			 $max_gid2 = $tmp_rst['gid']; 
//		}
//	}
//	$max_gamenum = max($max_gid1, $max_gid2);
	
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
	//gmode条件（游戏模式）
	$query_gtype = ''; $show_gtype_arr = array();
	for($i=0;$i<=20;$i++) {
		if(!empty(${'winner_show_gtype_'.$i})) $show_gtype_arr[] = $i;
	}
	sort($show_gtype_arr);
	if(!empty($show_gtype_arr)) {
		$query_gtype = "gametype IN ('".implode("','",$show_gtype_arr)."')";
	}
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
		$query_winner = "winner='$winner_show_winner'";
	}
	//先不拼接gid条件（当前局数指针），为了获得所有符合查找条件的结果gid，并获取最大和最小值
	$query_where = '';
	if(!empty($query_wmode) || !empty($query_gtype)  || !empty($query_winner)) {
		$query_where .= $query_wmode;
		$query_where .= (!empty($query_where) && !empty($query_gtype) ? ' AND ' : '') . $query_gtype;
		$query_where .= (!empty($query_where) && !empty($query_winner) ? ' AND ' : '') . $query_winner;
		$query_where = 'WHERE '.$query_where;
	}
	$query_count = "SELECT gid FROM {$wtablepre}history $query_where ORDER BY gid DESC";
	$result = $db->query($query_count);
	$result_num = $db->num_rows($result);
	
	$max_result_gamenum = $min_result_gamenum = 0;
	$winfo = array();
	$largest_mark = $larger_mark = $smaller_mark = $smallest_mark= 0;
	
	if($result_num){
		$wgidarr = Array();
		while($wgid = $db->fetch_array($result)) {
			$wgidarr[] = $wgid['gid'];
		}
		rsort($wgidarr);
		$max_result_gamenum = $wgidarr[0];
		$min_result_gamenum = $wgidarr[$result_num-1];
		//echo $max_result_gamenum.' '.$min_result_gamenum;
		
		//然后拼接含gid（当前局数指针）的WHERE条件
		$query_where = '';
		if(!empty($query_gid) || !empty($query_wmode) || !empty($query_gtype) || !empty($query_winner)) {
			$query_where .= $query_gid;
			$query_where .= (!empty($query_where) && !empty($query_wmode) ? ' AND ' : '') . $query_wmode;
			$query_where .= (!empty($query_where) && !empty($query_gtype) ? ' AND ' : '') . $query_gtype;
			$query_where .= (!empty($query_where) && !empty($query_winner) ? ' AND ' : '') . $query_winner;
			$query_where = 'WHERE '.$query_where;
		}
		$query_limit = "SELECT gid,wmode,winner,motto,gametype,vnum,gtime,gstime,getime,hdmg,hdp,hkill,hkp,winnernum,winnerteamID,winnerlist,winnerpdata,validlist FROM {$wtablepre}history $query_where ORDER BY gid DESC LIMIT $winlimit";
		//echo $query;
		$result = $db->query($query_limit);
		
		while($wdata = $db->fetch_array($result)) {
			$wdata['date'] = date("Y-m-d",$wdata['getime']);
			$wdata['time'] = date("H:i:s",$wdata['getime']);
			$wdata = winner_parse($wdata);
			$wdata['duration'] = !empty($wdata['validtime']) ? $wdata['getime']-$wdata['validtime'] : $wdata['getime'] - $wdata['gstime'];
			list($wiconImg, $wiconImgB) = \player\icon_parser_shell($wdata);
			$wdata['iconImg'] = $wiconImg;
			//APM
			list($vapm,$aapm) =  \apm\calc_winner_apm($wdata,$wdata['duration']);
			$wdata['apm_words'] = $vapm.' / '.$aapm;
			if('- / -' == $wdata['apm_words']) $wdata['apm_words'] = '<span class="grey b">-</span>';
			else $wdata['apm_words'] = str_replace('-', '<span class="grey b">-</span>', $wdata['apm_words']);
			$winfo[$wdata['gid']] = $wdata;
			
		}
		//判断分页情况
		$winfo_keys=array_keys($winfo);rsort($winfo_keys);
		$max_wdata_num=$winfo_keys[0];
		$min_wdata_num = $winfo_keys[sizeof($winfo_keys)-1];
		if($result_num > $winlimit){
			if(!isset($start) || !$start) $start = $max_result_gamenum;
			
			$largest_mark = $max_result_gamenum;
			$smallest_mark = max($min_result_gamenum, $winlimit);
			if(in_array($start, $wgidarr)) {
				$start_n = array_search($start, $wgidarr);
			}else{
				$tmp_wgidarr = $wgidarr;
				$tmp_wgidarr[] = $start;
				rsort($tmp_wgidarr);
				$start_n = array_search($start, $tmp_wgidarr);
			}
			if($start < $largest_mark) {
				//上一页，需要用到所有符合条件的gid数组$wgidarr
				//注意这里larger和smaller是从gid绝对值角度而言的，从数组角度larger的下标反而小，smaller的下标反而大
				$larger_n = max(0, $start_n - $winlimit);
				$larger_mark = $wgidarr[$larger_n];
				
//				$larger_mark = ceil(($start + $winlimit)/$winlimit)*$winlimit;
//				if($larger_mark > $largest_mark) $lager_mark = $largest_mark;
			}
			if($start > $smallest_mark) {
				$smaller_n = min($result_num-1, $start_n + $winlimit);
				$smaller_mark = $wgidarr[$smaller_n];
				
//				$smaller_mark = ceil(($start - $winlimit)/$winlimit)*$winlimit;
//				if($smaller_mark < $smallest_mark) $smaller_mark = $smallest_mark;
//				if($smaller_mark > $largest_mark) $smaller_mark = $largest_mark;
			}
		}
	}
	
	if ($command=='replay')
	{
		$result = $db->query("SELECT wmode FROM {$wtablepre}history where gid='$gnum'");
		if ($db->num_rows($result))
		{
			$zz = $db->fetch_array($result);
			$rep_winmode = $zz['wmode'];
		}
		else  $rep_winmode = 4;
	}
}

ob_start();
include template('winner');
ob_end_flush();

/* End of file command_winner.php */
/* Location: /include/pages/command_winner.php */