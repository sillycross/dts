<?php

define('CURSCRIPT', 'valid');

require './include/common.inc.php';
require './include/user.func.php';

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
	
	$db->query("UPDATE {$gtablepre}users SET gender='$gender', icon='$icon', motto='$motto', killmsg='$killmsg', card='$card', lastword='$lastword' WHERE username='".$udata['username']."'" );
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
	
	$cc=$card;
	$cardinfo=$cards[$cc];
	$r=$cardinfo['rare'];
	$cf=true;
	
	//通常和无限复活模式，C卡以上的卡只能进入1张
	if ($gametype==0 || $gametype==2){
		if (!in_array($cards[$cc]['rare'], array('C', 'M')))
		{
			$rst = $db->query("SELECT pid FROM {$tablepre}players WHERE card = '$cc' AND type = 0");
			if($db->num_rows($rst)) $cf=false;
		}
		
		if ($card_energy[$cc]<$cards[$cc]['energy'])
		{
			$cf=false;
		}
			
		if ($r=="S" && $now-$udata['cd_s']<86400)
		{
			$cf=false;
		}
		
		if ($gametype==2 && ($cc==97 || $cc==144)) $cf=false;
		
		if ($cf==false){
			//如果卡被顶掉则回到进入页面 
			header("Location: game.php");exit();
//			$cc=0;
//			$cardinfo=$cards[0];
		}
		else
		{
			$userCardData['cardenergy'][$cc]=0;
			\cardbase\save_cardenergy($userCardData,$cuser);
			
			if ($r=="S") 
				$db->query("UPDATE {$gtablepre}users SET cd_s='$now' WHERE username='".$udata['username']."'" );
		}
	}
	
	include_once GAME_ROOT.'./include/valid.func.php';
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

	$userCardData = \cardbase\get_user_cardinfo($cuser);
	$card_ownlist = $userCardData['cardlist'];;
	$card_energy = $userCardData['cardenergy'];
	$cardChosen = $userCardData['cardchosen'];

	/*
	 * $card_disabledlist id => errid
	 * id: 卡片ID errid: 不能使用这张卡的原因
	 * e0: S卡总体CD
	 * e1: 单卡CD
	 * e2: 有人于本局使用了同名卡
	 * e3: 本游戏模式不可用
	 *
	 * $card_error errid => msg
	 */
	$card_disabledlist=Array();
	$card_error=Array();
	
	$energy_recover_rate = \cardbase\get_energy_recover_rate($card_ownlist, $udata['gold']);
	
	//最低优先级错误原因：同名非C卡
	$result = $db->query("SELECT card FROM {$tablepre}players WHERE type = 0");
	$t=Array();
	while ($cdata = $db->fetch_array($result)) $t[$cdata['card']]=1;
	if($gametype < 10) //只有正规模式才限制卡片，房间模式不限
		foreach ($card_ownlist as $key)
			if (!in_array($cards[$key]['rare'], array('C', 'M')) && isset($t[$key])) 
			{
				$card_disabledlist[$key]='e2';
				$card_error['e2'] = '这张卡片暂时不能使用，因为本局已经有其他人使用了这张卡片<br>请下局早点入场吧！';
			}
	
	//次高优先级错误原因：单卡CD
	foreach ($card_ownlist as $key)
		if ($card_energy[$key]<$cards[$key]['energy'])
		{
			$t=($cards[$key]['energy']-$card_energy[$key])/$energy_recover_rate[$cards[$key]['rare']];
			$card_disabledlist[$key]='e1'.$key;
			$card_error['e1'.$key] = '这张卡片暂时不能使用，因为它目前正处于蓄能状态<br>这张卡片需要蓄积'.$cards[$key]['energy'].'点能量方可使用，预计在'.convert_tm($t).'后蓄能完成';
		}
	
	//最高优先级错误原因：s卡的24小时限制
	$card_error['e0'] = '这张卡片暂时不能使用，因为最近24小时内你已经使用过S卡了<br>在'.convert_tm(86400-($now-$udata['cd_s'])).'后你才能再次使用S卡';
	
	if (($now-$udata['cd_s'])<86400){
		foreach ($card_ownlist as $key)
			if ($cards[$key]['rare']=='S')
				$card_disabledlist[$key]='e0';
	}
	
	//最高优先级错误原因：本游戏模式不可用
	$card_error['e3'] = '这张卡片在本游戏模式下禁止使用！<br>';
	
	if ($gametype==2)	//deathmatch模式禁用蛋服和炸弹人
	{
		if (in_array(97,$card_ownlist)) $card_disabledlist[97]='e3';
		if (in_array(144,$card_ownlist)) $card_disabledlist[144]='e3';
	}
	
	$hideDisableButton = 1;
	$showCardUnavailableHint = 1;
	include template('valid');
}
?>