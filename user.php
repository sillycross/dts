<?php

define('CURSCRIPT', 'user');

require './include/common.inc.php';

eval(import_module('cardbase'));

$udata = udata_check();

if(!isset($mode)){
	$mode = 'show';
}
if($mode == 'edit') {
	$gamedata=Array();$gamedata['innerHTML']['info'] = '';
	$passarr = array();
	if($opass && $npass && $rnpass){
		$pass_right = true;
		$pass_check = pass_check($npass,$rnpass);
		if($pass_check!='pass_ok'){
			$gamedata['innerHTML']['info'] .= $_ERROR[$pass_check].'<br />';
			$pass_right = false;
		}
		$opass = create_cookiepass($opass);
		$npass = create_cookiepass($npass);
		if(!pass_compare($udata['username'], $opass, $udata['password'])){
			$gamedata['innerHTML']['info'] .= $_ERROR['wrong_pw'].'<br />';
			$pass_right = false;
		}
		if($pass_right){
			gsetcookie('pass',$npass);
			$nspass = create_storedpass($udata['username'], $npass);
			$passarr = array('password' => $nspass, 'alt_pswd' => 1);
			$gamedata['innerHTML']['info'] .= $_INFO['pass_success'].'<br />';
		}else{
			//$passqry = '';
			$gamedata['innerHTML']['info'] .= $_INFO['pass_failure'].'<br />';
		}
	}else{
		//$passqry = '';
		$gamedata['innerHTML']['info'] .= $_INFO['pass_failure'].'<br />';
	}
	$cardlist = \cardbase\get_cardlist_energy_from_udata($udata)[0];
	if(!in_array($card, $cardlist)) {
		$card = 0;
	}

	$updarr = array(
		'gender' => $gender,
		'icon' => $icon,
		'motto' => $motto,
		'killmsg' => $killmsg,
		'lastword' => $lastword,
		'card' => $card,
		'u_templateid' => $templateid
	);
	if(!empty($passarr)) $updarr = array_merge($updarr, $passarr);
	
	update_udata_by_username($updarr, $cuser);
	
	$gamedata['innerHTML']['info'] .= $_INFO['data_success'];

	$gamedata['value']['opass'] = $gamedata['value']['npass'] = $gamedata['value']['rnpass'] = '';
	if(isset($error)){$gamedata['innerHTML']['error'] = $error;}
	ob_clean();
	$jgamedata = gencode($gamedata);
	echo $jgamedata;
	ob_end_flush();
	
} else {
	//$ustate = 'edit';
	extract($udata);
	$iconarray = get_iconlist($icon);
	$select_icon = $icon;
	
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
	
	include template('user');
}

?> 