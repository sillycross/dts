<?php

define('CURSCRIPT', 'user_profile');

require './include/common.inc.php';

if ($server_addr!=$cache_server_addr && $is_cache_server)
{
        header("Location: {$server_addr}user_profile.php");
        exit(); 
}


require './include/user.func.php';

$_REQUEST = gstrfilter($_REQUEST);
if ($_REQUEST["playerID"]=="")
{
	if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); }

	$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$cuser'");
	if(!$db->num_rows($result)) { gexit($_ERROR['login_check'],__file__,__line__); }
	$udata = $db->fetch_array($result);
	if($udata['password'] != $cpass) { gexit($_ERROR['wrong_pw'], __file__, __line__); }
	if($udata['groupid'] <= 0) { gexit($_ERROR['user_ban'], __file__, __line__); }

	extract($udata);
	$curuser=true;
}
else
{
	$uname=urldecode($_REQUEST["playerID"]);
	$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$uname'");
	if(!$db->num_rows($result)) { gexit($_ERROR['user_not_exists'],__file__,__line__); }
	$udata = $db->fetch_array($result);
	extract($udata);
	$curuser=false;
	if ($uname==$cuser) $curuser=true;
}

$iconarray = get_iconlist($icon);
$select_icon = $icon;
$winning_rate=$validgames?round($wingames/$validgames*100)."%":'0%';
require config('card',$gamecfg);
$carr=$carddesc[$card];
$cr=$carr['rare'];
$cf=true;$sf=true;$af=true;$bf=true;
if (($now-$udata['cd_s'])<86400){
	$sf=false;
	$ntime=$udata['cd_s']+86400;
	list($min,$hour,$day,$month,$year)=explode(',',date("i,H,j,n,Y",$ntime));
	$std=$year."年".$month."月".$day."日".$hour."时".$min."分";
}
if (($now-$udata['cd_a'])<86400){
	$af=false;
	$ntime=$udata['cd_a']+86400;
	list($min,$hour,$day,$month,$year)=explode(',',date("i,H,j,n,Y",$ntime));
	$atd=$year."年".$month."月".$day."日".$hour."时".$min."分";
}
if (($now-$udata['cd_b'])<10800){
	$bf=false;
	$ntime=$udata['cd_b']+10800;
	list($min,$hour,$day,$month,$year)=explode(',',date("i,H,j,n,Y",$ntime));
	$btd=$year."年".$month."月".$day."日".$hour."时".$min."分";
}
if ($cr=="S"){
	$rarecolor="orange";
	if (!$sf) $cf=false;
}else if ($cr=='A'){
	$rarecolor="linen";
	if (!$af) $cf=false;
}else if ($cr=='B'){
	$rarecolor="brickred";
	if (!$bf) $cf=false;
}else if ($cr=='C'){
	$rarecolor="seagreen";
}
include template('user_profile');

