<?php

define('CURSCRIPT', 'valid');

require './include/common.inc.php';
require './include/user.func.php';

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); }
if($gamestate < 20) { gexit($_ERROR['no_start'],__file__,__line__); }
//if($gamestate >= 30) { gexit($_ERROR['valid_stop'],__file__,__line__); }

$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$cuser'");
if(!$db->num_rows($result)) { gexit($_ERROR['login_check'],__file__,__line__); }
$udata = $db->fetch_array($result);
if($udata['password'] != $cpass) { gexit($_ERROR['wrong_pw'], __file__, __line__); }
if($udata['groupid'] <= 0) { gexit($_ERROR['user_ban'], __file__, __line__); }

if($gamestate >= 30 && $udata['groupid'] < 6 && $cuser != $gamefounder) {
	gexit($_ERROR['valid_stop'],__file__,__line__);
}
require config('card',$gamecfg);
$jfile=GAME_ROOT."./gamedata/cache/card.json";
$cdfile=GAME_ROOT."./gamedata/cache/card_1.php";
if ((!file_exists($jfile)) || (filemtime($cdfile) > filemtime($jfile))){
	$jdesc=json_encode($carddesc,JSON_UNESCAPED_UNICODE);
	writeover($jfile,$jdesc);
}

if($mode == 'enter') {
	if($iplimit) {
		$result = $db->query("SELECT * FROM {$gtablepre}users AS u, {$tablepre}players AS p WHERE u.ip='{$udata['ip']}' AND ( u.username=p.name AND p.type=0)");
		if($db->num_rows($result) > $iplimit) { gexit($_ERROR['ip_limit'],__file__,__line__); }
	}	

	$ip = real_ip();
	if ($udata['cardlist']==""){
		$ucl="0";
	}else{
		$ucl=$udata['cardlist'];
	}
	$carr = explode('_',$ucl);
	$cflag=0;
	foreach ($carr as $val){
		if ($val==$card){
			$cflag=true;
			break;
		}
	}
	if (!$cflag) $card=0;
	$db->query("UPDATE {$gtablepre}users SET gender='$gender', icon='$icon', motto='$motto', killmsg='$killmsg', card='$card',lastword='$lastword' ,cardlist='".$ucl."' WHERE username='".$udata['username']."'" );
	if($validnum >= $validlimit) {
		gexit($_ERROR['player_limit'],__file__, __line__);
	}
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
	if($db->num_rows($result)) {
		gexit($_ERROR['player_exist'], __file__, __line__);
	}
	if ($gender !== 'm' && $gender !== 'f'){
		$gender = 'm';
	}
	
	require config('card',$gamecfg);
	$cc=$card;
	$cardinfo=$carddesc[$cc];
	$r=$cardinfo['rare'];
	$cf=true;
	if ($r=="S"){
		if (($now-$udata['cd_s'])<86400){
			$cf=false;
		}else{
			$db->query("UPDATE {$gtablepre}users SET cd_s='$now' WHERE username='".$udata['username']."'" );
		}
	}else if ($r=="A"){
		if (($now-$udata['cd_a'])<43200){
			$cf=false;
		}else{
			$db->query("UPDATE {$gtablepre}users SET cd_a='$now' WHERE username='".$udata['username']."'" );
		}
	}else if ($r=="B"){
		if (($now-$udata['cd_b'])<10800){
			$cf=false;
		}else{
			$db->query("UPDATE {$gtablepre}users SET cd_b='$now' WHERE username='".$udata['username']."'" );
		}
	}
	if ($cf==false){
		$cc=0;
		$cardinfo=$carddesc[0];
	}
	include_once GAME_ROOT.'./include/valid.func.php';
	enter_battlefield($cuser,$cpass,$gender,$icon,$cc);
	
	
	include template('validover');
} elseif($mode == 'notice') {
	include template('notice');
} elseif($mode == 'tutorial') {
	if(!isset($tmode)){$tmode = 0;}
	$nexttmode = $tmode +1;
	include template('tutorial');
} else {
	extract($udata);
	if ($udata['cardlist']==""){
		$udata['cardlist']="0";
		$cardlist="0";
		$db->query("UPDATE {$gtablepre}users SET cardlist='$cardlist' WHERE username = '$username'");
	}
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
	if($db->num_rows($result)) {
		header("Location: game.php");exit();
	}

	if($validnum >= $validlimit) {
		gexit($_ERROR['player_limit'],__file__,__line__);
	}
	$iconarray = get_iconlist($icon);
	$select_icon = $icon;

	$r=$carddesc[$card]['rare'];
	$cad=$udata['card'];
	$carr = explode('_',$udata['cardlist']);
	$clist = Array();
	foreach($carr as $key => $val){
		$clist[$key] = $val;
	}
	$cad=$card;
	$sf=true;$af=true;$bf=true;
	if (($now-$udata['cd_s'])<86400){
		$sf=false;
	}
	if (($now-$udata['cd_a'])<43200){
		$af=false;
	}
	if (($now-$udata['cd_b'])<10800){
		$bf=false;	
	}
	$stime=$udata['cd_s']+86400;
	list($min,$hour,$day,$month,$year)=explode(',',date("i,H,j,n,Y",$stime));
	$std=$year."年".$month."月".$day."日".$hour."时".$min."分";
	$atime=$udata['cd_a']+43200;
	list($min,$hour,$day,$month,$year)=explode(',',date("i,H,j,n,Y",$atime));
	$atd=$year."年".$month."月".$day."日".$hour."时".$min."分";
	$btime=$udata['cd_b']+10800;
	list($min,$hour,$day,$month,$year)=explode(',',date("i,H,j,n,Y",$btime));
	$btd=$year."年".$month."月".$day."日".$hour."时".$min."分";
	include template('valid');
}
?>


