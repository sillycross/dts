<?php

define('CURSCRIPT', 'user');

require './include/common.inc.php';

eval(import_module('cardbase'));

$udata = udata_check();

if(!isset($mode)){
	$mode = 'show';
}
if($mode == 'edit') {
	//各项参数初始化
	$gamedata=Array();$gamedata['innerHTML']['info'] = '';
	$passarr = array();
	//修改密码判定
	if($opass && $npass && $rnpass){
		$pass_right = true;
		$pass_check = pass_check($npass,$rnpass);
		if($pass_check!='pass_ok'){
			$gamedata['innerHTML']['info'] .= $_ERROR[$pass_check];
			$pass_right = false;
		}
		$opass = create_cookiepass($opass);
		$npass = create_cookiepass($npass);
		if(!pass_compare($udata['username'], $opass, $udata['password'])){
			$gamedata['innerHTML']['info'] .= $_ERROR['wrong_pw'];
			$pass_right = false;
		}
		if($pass_right){
			gsetcookie('pass',$npass);
			$nspass = create_storedpass($udata['username'], $npass);
			$passarr = array('password' => $nspass, 'alt_pswd' => 1);
			$gamedata['innerHTML']['info'] .= $_INFO['pass_success'];
		}else{
			$gamedata['innerHTML']['info'] .= $_INFO['pass_failure'];
		}
	}else{
		$gamedata['innerHTML']['info'] .= $_INFO['pass_failure'];
	}
	$cardlist = \cardbase\get_cardlist_energy_from_udata($udata)[0];
	if(!in_array($card, $cardlist)) {
		$card = 0;
	}

	$updarr = Array();
	//有字段的资料的修改
	$updkeys = Array('gender', 'icon', 'motto', 'killmsg', 'lastword', 'card', 'u_templateid');
	
	foreach($updkeys as $pm) {
		if(isset(${$pm}) && ${$pm} != $udata[$pm]) {
			$updarr[$pm] = ${$pm};
		}
	}
	//$u_settings字段的修改。这个字段是用gencode格式储存的
	$u_settings = \user_settings\get_u_settings($udata);
	
	$ustgkeys = Array(
		'skip_opening' => 'int', //是否跳过开场剧情
		'item_auto_merge' => 'int', //是否自动合并可合并的道具
	);
	$ustgflag = 0;
	foreach($ustgkeys as $pm => $tp) {
		if(isset(${$pm}) && (!isset($u_settings[$pm]) || ${$pm} != $u_settings[$pm])) {
			$u_settings[$pm] = ${$pm};
			if('int' == $tp) $u_settings[$pm] = (int)$u_settings[$pm];
			$ustgflag = 1;
		}
	}
	if($ustgflag) {
		$updarr['u_settings'] = gencode($u_settings);
	}

	//把密码的修改合并进$updarr
	if(!empty($passarr)) 
		$updarr = array_merge($updarr, $passarr);
	
	if(!empty($updarr)) {
		update_udata_by_username($updarr, $cuser);
		$gamedata['innerHTML']['info'] .= $_INFO['data_success'];
	}else{
		$gamedata['innerHTML']['info'] .= $_INFO['data_failure'];
	}

	//$gamedata['innerHTML']['info'] .= var_export($updarr,1).' '.var_export($u_settings, 1);

	$gamedata['value']['opass'] = $gamedata['value']['npass'] = $gamedata['value']['rnpass'] = '';
	if(isset($error)){$gamedata['innerHTML']['error'] = $error;}
	ob_clean();
	$jgamedata = gencode($gamedata);
	echo $jgamedata;
	ob_end_flush();
	
} else {
	//把$udata中的内容全部解出来
	extract($udata);
	$iconarray = get_iconlist($icon);
	$select_icon = $icon;
	
	//卡片部分
	$userCardData = \cardbase\get_user_cardinfo($cuser);
	$card_ownlist = $userCardData['cardlist'];;
	$card_energy = $userCardData['cardenergy'];
	$card_data_fetched = $userCardData['card_data'];
	$cardChosen = $userCardData['cardchosen'];
	$card_disabledlist=Array();
	$card_error=Array();
	$packlist = \cardbase\pack_filter($packlist);
	
	$card_achieved_list = array();
	if(defined('MOD_ACHIEVEMENT_BASE')) {
		$d_achievements = \achievement_base\decode_achievements($udata);
		if(!empty($d_achievements['326'])) $card_achieved_list = $d_achievements['326'];
	}

	//u_settings部分，gdecode之后全部解出来，用于显示
	if(!empty($u_settings)) {
		$u_settings = gdecode($u_settings, 1);
	}
	if(!is_array($u_settings)) {
		$u_settings = Array();
	}
	extract($u_settings);
	
	include template('user');
}

?> 