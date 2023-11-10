<?php
if(!defined('IN_GAME')) {
	exit('Access Denied');
}

eval(import_module('sys','player','map','input'));

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

//接收入场命令，这里是在修改用户资料和卡片的页面提交的
if($mode == 'enter') {
	$ip = !empty($valid_ip) ? $valid_ip : $udata['ip'];
	
	//达到了IP限制
	if($iplimit) {
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE type=0 AND ip='$ip'");
		if($db->num_rows($result) >= $iplimit) {
			gexit($_ERROR['ip_limit'],__file__,__line__);
			return;
		}
	}	
	
	//激活数达到上限
	if($validnum >= $validlimit) {
		gexit($_ERROR['player_limit'],__file__, __line__);
		return;
	}
	//玩家已存在
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
	if($db->num_rows($result)) {
		gexit($_ERROR['player_exist'], __file__, __line__);
		return;
	}
	
	//载入提交的用户资料，口头禅之类的
	if ($gender !== 'm' && $gender !== 'f'){
		$gender = 'f';
	}
	$updatearr = array(
		'gender' => $gender,
		'icon' => $icon,
		'motto' => $motto,
		'killmsg' => $killmsg,
		'lastword'=>$lastword,
		'ip'=>$ip
	);
	
	//接受对用户资料的修改。注意：就算卡片不适用，这里也先接受口头禅之类的修改
	//注意在enter_battlefield()里还有对卡片和实际进入游戏局数的修改，也就是会更新两次
	update_udata_by_username($updatearr, $udata['username']);
	
	$enterable = true;
	
	//判定卡片数据是否合理
	//这部分和cardselect.htm的变量命名都非常混乱，但出于能跑就不动的考虑，就不改了
	if(defined('MOD_CARDBASE')){
		eval(import_module('cardbase'));
		$userCardData = \cardbase\get_user_cardinfo($cuser);
		$card_ownlist = $userCardData['cardlist'];//这里这两个变量故意起了不一样的名字，历史原因不改了
		$card_energy = $userCardData['cardenergy'];
		$card_data_fetched = $userCardData['card_data'];//这里也跟上面一样故意起不一样的名字吧，有问蹄再改回来
		$cardChosen = $card;
		$hideDisableButton = 1;
		list($cardChosen, $card_ownlist, $packlist, $hideDisableButton) = \cardbase\card_validate_display($cardChosen, $card_ownlist, $packlist, $hideDisableButton);
		//提交了使用并未拥有的卡片的命令
		if (!\cardbase\check_card_in_ownlist($card, $card_ownlist)) {
			$enterable = false;
			$card = 0;
			$updatearr = Array('card' => 0);
			update_udata_by_username($updatearr, $udata['username']);//立刻更新数据库中的当前卡片，避免一些BUG导致的死循环
		}else{
			$cc=$card;
			$cardinfo=$cards[$cc];
			$r=$cardinfo['rare'];
			$cf=true;
			$updatearr = Array();
			list($card_disabledlist,$card_error) = \cardbase\card_validate($udata);
			if(!empty($card_disabledlist[$cc])) //当前卡片无法使用
			{
				$enterable = false;
			}
			elseif($cardinfo['energy'] && in_array($gametype, $card_need_charge_gtype)) //当卡片需要能量，且游戏模式为卡片模式、无限复活模式、荣耀模式、极速模式时，更新卡片CD时间
			{
				if(!empty($card_cooldown_discount_gtype[$gametype])) {
					$userCardData['cardenergy'][$cc] = round($cards[$cc]['energy'] * $card_cooldown_discount_gtype[$gametype]);//荣誉模式CD减半
				}else {
					$userCardData['cardenergy'][$cc] = 0;
				}
				//\cardbase\save_cardenergy($userCardData,$cuser);
				//更新卡片冷却信息。因为上面没有改$card_energy，这里也只能用原数组
				\cardbase\save_cardenergy_to_db($card_ownlist, $userCardData['cardenergy'], $card_data_fetched, $udata);
				//以下是更新卡片类别的冷却，感觉可以再重构得更优美一点，跟冷却本身合并，但是回头再说吧
				if(!empty($cardtypecd[$r])){
					$cardtype_cd_period = $cardtypecd[$r];
					if(!empty($card_cooldown_discount_gtype[$gametype])) {
						$cardtype_cd_period *= $card_cooldown_discount_gtype[$gametype];//荣誉模式、极速模式类别CD减半
					}
					if($cards[$cc]['energy'] < 100) {//如果卡片本身的能量小于100，那么根据能量叠加一层折数
						$cardtype_cd_period *= $cards[$cc]['energy']/100;
					}
					$cardtype_cd_period = round($cardtype_cd_period);
					$updatearr ['cd_'.strtolower($r)] = $now + $cardtype_cd_period - $cardtypecd[$r];//实际记录的是(到期时间-原始类别CD)的时间戳
				}
			}
			if($enterable) {
				if(in_array($gametype, $card_need_charge_gtype) && !empty($updatearr)) {//能自选卡片的模式，把卡片和CD时间写回数据库
					$updatearr['card'] = $card;
					update_udata_by_username($updatearr, $udata['username']);
				}
			}
		}
	}
	
	
	
	if(false==$enterable) {
		echo 'redirect:valid.php';
		return;
	}
	
	enter_battlefield($cuser,$cpass,$gender,$icon,$cc,$ip);
	
	//进入游戏
	echo 'redirect:game.php';
	
	
} elseif($mode == 'notice') {
	//遗留分支
	include template('notice');
} else {
	extract($udata);
	
	eval(import_module('cardbase'));
	
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
	$card_data_fetched = $userCardData['card_data'];
	$cardChosen = $userCardData['cardchosen'];
	$packlist = \cardbase\pack_filter($packlist);
	$hideDisableButton = 1;
	list($cardChosen, $card_ownlist, $packlist, $hideDisableButton) = \cardbase\card_validate_display($cardChosen, $card_ownlist, $packlist, $hideDisableButton);
	list($card_disabledlist,$card_error) = \cardbase\card_validate($udata);
	
	
	$d_achievements = \achievement_base\decode_achievements($udata);
	$card_achieved_list = array();
	if(!empty($d_achievements['326'])) $card_achieved_list = $d_achievements['326'];
	
	$showCardUnavailableHint = 1;
	
	//标准模式或者禁用了挑战者卡的模式（一般是特殊局）屏蔽卡片搜索条
	$no_select = (0 == $gametype || !empty($card_disabledlist[0])) ? 1 : 0;
	include template('valid');
}

/* End of file command_valid.php */
/* Location: /include/pages/command_valid.php */