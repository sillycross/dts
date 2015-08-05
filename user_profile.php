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

include template('user_profile');

