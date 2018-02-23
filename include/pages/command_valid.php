<?php
if(!defined('IN_GAME')) {
	exit('Access Denied');
}

eval(import_module('sys','player','map','input'));

include_once './include/user.func.php';
include_once './include/valid.func.php';

if(!$cuser||!$cpass) {
	gexit($_ERROR['no_login'],__file__,__line__);
	return;
}
if($gamestate < 20) {
	gexit($_ERROR['no_start'],__file__,__line__);
	return;
}
$udata = udata_check();
if(!$udata) return;

if($gamestate >= 30 && $udata['groupid'] < 6 && $cuser != $gamefounder) {
	gexit($_ERROR['valid_stop'],__file__,__line__);
	return;
}

eval(import_module('cardbase'));

//入场
if($mode == 'enter') {
	if($iplimit) {
		$result = $db->query("SELECT * FROM {$gtablepre}users AS u, {$tablepre}players AS p WHERE u.ip='{$udata['ip']}' AND ( u.username=p.name AND p.type=0)");
		if($db->num_rows($result) > $iplimit) {
			gexit($_ERROR['ip_limit'],__file__,__line__);
			return;
		}
	}	

	//$ip = real_ip();
	$ip = $udata['ip'];
	
	$userCardData = \cardbase\get_user_cardinfo($cuser);
	$card_ownlist = $userCardData['cardlist'];
	$card_energy = $userCardData['cardenergy'];
	if (!in_array($card,$card_ownlist)) {
		$card=0;
	}
	
	if ($gender !== 'm' && $gender !== 'f'){
		$gender = 'f';
	}
	$db->query("UPDATE {$gtablepre}users SET gender='$gender', icon='$icon', motto='$motto', killmsg='$killmsg', card='$card', lastword='$lastword' WHERE username='".$udata['username']."'" );
	if($validnum >= $validlimit) {
		gexit($_ERROR['player_limit'],__file__, __line__);
		return;
	}
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
	if($db->num_rows($result)) {
		gexit($_ERROR['player_exist'], __file__, __line__);
		return;
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
	elseif($cardinfo['energy'] && in_array($gametype, array(2,4,18,19))) //当卡片需要能量，且游戏模式为卡片模式、无限复活模式、荣耀模式、极速模式时，更新卡片CD时间
	{
		if(18 == $gametype || 19 == $gametype) $userCardData['cardenergy'][$cc] = round($cards[$cc]['energy'] / 2);//荣誉模式CD减半
		else $userCardData['cardenergy'][$cc] = 0;
		\cardbase\save_cardenergy($userCardData,$cuser);
		if(!empty($cardtypecd[$r])){
			$ctcdtime = $now;
			if(18 == $gametype || 19 == $gametype) $ctcdtime -= round($cardtypecd[$r] / 2);//荣誉模式、极速模式类别CD减半
			$setquery = 'cd_'.strtolower($r)."='$ctcdtime'";
			$db->query("UPDATE {$gtablepre}users SET $setquery WHERE username='".$udata['username']."'" );
		}
	}
	
	if(false==$enterable) {
		echo 'redirect:valid.php';
		return;
	}
	
	enter_battlefield($cuser,$cpass,$gender,$icon,$cc,$ip);
	
	//现在入场跳过validover页面直接进开局提示页面
	include template('notice');
	//include template('validover');
} elseif($mode == 'notice') {
	include template('notice');
} else {
	extract($udata);
	
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
	if($db->num_rows($result)) {
		echo 'redirect:game.php';
		return;
	}

	if($validnum >= $validlimit) {
		gexit($_ERROR['player_limit'],__file__,__line__);
		return;
	}
	$iconarray = get_iconlist($icon);
	$select_icon = $icon;

	$userCardData = \cardbase\get_user_cardinfo($udata['username']);
	$card_ownlist = $userCardData['cardlist'];
	$card_energy = $userCardData['cardenergy'];
	$cardChosen = $userCardData['cardchosen'];
	$packlist = \cardbase\pack_filter($packlist);
	$hideDisableButton = 1;
	list($card_disabledlist,$card_error) = card_validate($udata);
	
	$d_achievements = \achievement_base\decode_achievements($udata);
	$card_achieved_list = array();
	if(!empty($d_achievements['326'])) $card_achieved_list = $d_achievements['326'];
	
	$showCardUnavailableHint = 1;
	include template('valid');
}

/* End of file command_valid.php */
/* Location: /include/pages/command_valid.php */