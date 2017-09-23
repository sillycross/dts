<?php

define('CURSCRIPT', 'valid');

require './include/common.inc.php';
require './include/user.func.php';
require './include/valid.func.php';

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); }
if($gamestate < 20) { gexit($_ERROR['no_start'],__file__,__line__); }
//if($gamestate >= 30) { gexit($_ERROR['valid_stop'],__file__,__line__); }

$udata = udata_check();
//$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$cuser'");
//if(!$db->num_rows($result)) { gexit($_ERROR['login_check'],__file__,__line__); }
//$udata = $db->fetch_array($result);
//if($udata['password'] != $cpass) { gexit($_ERROR['wrong_pw'], __file__, __line__); }
//if($udata['groupid'] <= 0) { gexit($_ERROR['user_ban'], __file__, __line__); }

if($gamestate >= 30 && $udata['groupid'] < 6 && $cuser != $gamefounder) {
	gexit($_ERROR['valid_stop'],__file__,__line__);
}

eval(import_module('cardbase'));

//入场
if($mode == 'enter') {
	if($iplimit) {
		$result = $db->query("SELECT * FROM {$gtablepre}users AS u, {$tablepre}players AS p WHERE u.ip='{$udata['ip']}' AND ( u.username=p.name AND p.type=0)");
		if($db->num_rows($result) > $iplimit) { gexit($_ERROR['ip_limit'],__file__,__line__); }
	}	

	$ip = real_ip();
	
	$userCardData = \cardbase\get_user_cardinfo($cuser);
	$card_ownlist = $userCardData['cardlist'];;
	$card_energy = $userCardData['cardenergy'];
	
	if (!in_array($card,$card_ownlist)) $card=0;
	
	if ($gender !== 'm' && $gender !== 'f'){
		$gender = 'f';
	}
	$db->query("UPDATE {$gtablepre}users SET gender='$gender', icon='$icon', motto='$motto', killmsg='$killmsg', card='$card', lastword='$lastword' WHERE username='".$udata['username']."'" );
	if($validnum >= $validlimit) {
		gexit($_ERROR['player_limit'],__file__, __line__);
	}
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
	if($db->num_rows($result)) {
		gexit($_ERROR['player_exist'], __file__, __line__);
	}
	
	$enterable = true;
	$cc=$card;
	$cardinfo=$cards[$cc];
	$r=$cardinfo['rare'];
	$cf=true;
	
	list($card_disabledlist,$card_error) = card_validate($udata);
	if(!empty($card_disabledlist[$cc])) //当前卡片无法使用
	{
		$enterable = false;
	}
	elseif(in_array($gametype, array(2,4))) //游戏模式为标准或者无限复活时，刷新卡片CD时间
	{
		$userCardData['cardenergy'][$cc]=0;
		\cardbase\save_cardenergy($userCardData,$cuser);
		if(!empty($cardtypecd[$r])){
			$setquery = 'cd_'.strtolower($r)."='$now'";
			$db->query("UPDATE {$gtablepre}users SET $setquery WHERE username='".$udata['username']."'" );
		}
	}
	
	if(false==$enterable) {
		header("Location: game.php");exit();
	}
	
	enter_battlefield($cuser,$cpass,$gender,$icon,$cc);
	
	//现在入场跳过validover页面直接进开局提示页面
	include template('notice');
	//include template('validover');
} elseif($mode == 'notice') {
	include template('notice');
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

	$userCardData = \cardbase\get_user_cardinfo($udata['username']);
	$card_ownlist = $userCardData['cardlist'];
	$card_energy = $userCardData['cardenergy'];
	$cardChosen = $userCardData['cardchosen'];
	
	list($card_disabledlist,$card_error) = card_validate($udata);
	
	$hideDisableButton = 1;
	$showCardUnavailableHint = 1;
	include template('valid');
}
?>