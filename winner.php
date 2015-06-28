<?php

define('CURSCRIPT', 'winner');

require './include/common.inc.php';

eval(import_module('player'));

if(!isset($command)){$command = 'ref';}
if($command == 'info') {
	$result = $db->query("SELECT * FROM {$tablepre}winners WHERE gid='$gnum' LIMIT 1");
	$pdata = $db->fetch_array($result);
	$pdata['gdate'] = floor($pdata['gtime']/3600).':'.floor($pdata['gtime']%3600/60).':'.($pdata['gtime']%60);
	$pdata['gsdate'] = date("m/d/Y H:i:s",$pdata['gstime']);
	$pdata['gedate'] = date("m/d/Y H:i:s",$pdata['getime']);
	\player\load_playerdata($pdata);
	\player\init_playerdata();
	\player\init_profile();
	extract($pdata);
} elseif($command == 'news') {
	$hnewsfile = GAME_ROOT."./gamedata/bak/{$gnum}_newsinfo.html";
	if(file_exists($hnewsfile)){
		$hnewsinfo = readover($hnewsfile);
	}
} else {
	if(!isset($start) || !$start){
		$result = $db->query("SELECT gid,name,icon,gd,wep,wmode,getime,motto,hdp,hdmg,hkp,hkill FROM {$tablepre}winners ORDER BY gid desc LIMIT $winlimit");
	} else {
		$result = $db->query("SELECT gid,name,icon,gd,wep,wmode,getime,motto,hdp,hdmg,hkp,hkill FROM {$tablepre}winners WHERE gid<='$start' ORDER BY gid desc LIMIT $winlimit");
	}
	while($wdata = $db->fetch_array($result)) {
		$wdata['date'] = date("Y-m-d",$wdata['getime']);
		$wdata['time'] = date("H:i:s",$wdata['getime']);
		$wdata['iconImg'] = $wdata['gd'] == 'f' ? 'f_'.$wdata['icon'].'.gif' : 'm_'.$wdata['icon'].'.gif';
		$winfo[$wdata['gid']] = $wdata;
	}
	$listnum = floor($gamenum/$winlimit);

	for($i=0;$i<$listnum;$i++) {
		$snum = ($listnum-$i)*$winlimit;
		$enum = $snum-$winlimit+1;
		$listinfo .= "<input style='width: 120px;' type='button' value='{$snum} ~ {$enum} 回' onClick=\"document['list']['start'].value = '$snum'; document['list'].submit();\">";
		if(is_int(($i+1)/3)&&$i){$listinfo .= '<br>';}
	}
}

include template('winner');

?>