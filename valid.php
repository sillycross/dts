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

if($mode == 'enter') {
	if($iplimit) {
		$result = $db->query("SELECT * FROM {$gtablepre}users AS u, {$tablepre}players AS p WHERE u.ip='{$udata['ip']}' AND ( u.username=p.name AND p.type=0)");
		if($db->num_rows($result) > $iplimit) { gexit($_ERROR['ip_limit'],__file__,__line__); }
	}	

	$ip = real_ip();
	$db->query("UPDATE {$gtablepre}users SET gender='$gender', icon='$icon', motto='$motto', killmsg='$killmsg', lastword='$lastword' WHERE username='".$udata['username']."'" );
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
	
	include_once GAME_ROOT.'./include/valid.func.php';
	enter_battlefield($cuser,$cpass,$gender,$icon);
	
	include template('validover');
} elseif($mode == 'notice') {
	include template('notice');
} elseif($mode == 'tutorial') {
	if(!isset($tmode)){$tmode = 0;}
	$nexttmode = $tmode +1;
	include template('tutorial');
} else {
	extract($udata);
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
	if($db->num_rows($result)) {
		header("Location: game.php");exit();
	}

	if($validnum >= $validlimit) {
		gexit($_ERROR['player_limit'],__file__,__line__);
	}
	$iconarray = get_iconlist($icon);
	$select_icon = $icon;
	include template('valid');
}

/*
function makeclub() {
	global $wp,$wk,$wg,$wc,$wd,$wf,$money,$mhp,$msp,$hp,$sp,$att,$def;
	$wp = $wk = $wg = $wc = $wd = $wf = 0;
	$dice = rand(0,105);
	if($dice < 10)		{$club = 1;$wp = 30;}//殴25
	elseif($dice < 20)	{$club = 2;$wk = 30;}//斩25
	elseif($dice < 30)	{$club = 3;$wc = 30;}//投25
	elseif($dice < 40)	{$club = 4;$wg = 30;}//射25
	elseif($dice < 50)	{$club = 5;$wd = 20;}//爆25
	elseif($dice < 55)	{$club = 6;}//移动、探索消耗减
	elseif($dice < 60)	{$club = 7;}//P(HACK)=1
	elseif($dice < 65)	{$club = 8;}//查毒可
	elseif($dice < 75)	{$club = 9;$wf = 20;}//能使用必杀，灵25
	elseif($dice < 80)	{$club = 10;}//攻击熟练+2
	elseif($dice < 85)	{$club = 11;$money = 500;}//出击钱数500
	elseif($dice < 90)	{$club = 12;$wp = $wk = $wg = $wc = $wd = $wf = 50;}//全熟练50
	elseif($dice < 95)	{$club = 13;$mhp = $mhp + 200;$hp = $mhp;}//生命上限提高200
	elseif($dice < 100)	{$club = 14;$att = $att + 200;$def = $def + 200;}//攻防+100
	elseif($dice <= 105) {$club = 18;}//回复量增加
	else				{$club = makeclub();}
	return $club;
}
*/
?>


