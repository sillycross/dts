<?php

define('CURSCRIPT', 'end');

require './include/common.inc.php';
if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); } 
$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
if(!$db->num_rows($result)) { header("Location: index.php");exit(); }

$pdata = $db->fetch_array($result);
if($pdata['pass'] != $cpass) {
	$tr = $db->query("SELECT `password` FROM {$gtablepre}users WHERE username='$cuser'");
	$tp = $db->fetch_array($tr);
	$password = $tp['password'];
	include_once './include/user.func.php';
	if(pass_compare($cuser, $cpass, $password)) {
		$db->query("UPDATE {$tablepre}players SET pass='$password' WHERE name='$cuser'");
	} else {
		gexit($_ERROR['wrong_pw'],__file__,__line__);
	}
}

//玩家游戏胜利后可选择下一局状况
$next_gamevars_display = \sys\user_display_setting_next_gamevars();
//还没完成

eval(import_module('player'));
\player\load_playerdata($pdata);
\player\init_playerdata();
extract($pdata);

if($hp<=0 || $state>=10) {
	$result = $db->query("SELECT lastword FROM {$gtablepre}users WHERE username='$name'");
	$motto = $db->result($result,0);
	$dtime = date("Y年m月d日H时i分s秒",$endtime);
	if($bid) {
		$result = $db->query("SELECT name FROM {$tablepre}players WHERE pid='$bid'");
		if($db->num_rows($result)) { $kname = $db->result($result,0); }
	}
}

include template('end');


?>