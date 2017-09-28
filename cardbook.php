<?php

define('CURSCRIPT', 'cardbook');

require './include/common.inc.php';
require './include/user.func.php';

eval(import_module('cardbase'));

$_REQUEST = gstrfilter($_REQUEST);

if ($_REQUEST["playerID"]=="") {
	$udata = udata_check();
//	if (!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); }
//
//	$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$cuser'");
//	if(!$db->num_rows($result)) { gexit($_ERROR['login_check'],__file__,__line__); }
//	$udata = $db->fetch_array($result);
//	if($udata['password'] != $cpass) { gexit($_ERROR['wrong_pw'], __file__, __line__); }
//	if($udata['groupid'] <= 0) { gexit($_ERROR['user_ban'], __file__, __line__); }

	$n=$cuser;
	extract($udata);
	$curuser=true;
} else {
	$uname=urldecode($_REQUEST["playerID"]);
	$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$uname'");
	if(!$db->num_rows($result)) { gexit($_ERROR['user_not_exists'],__file__,__line__); }
	$udata = $db->fetch_array($result);
	extract($udata);
	$curuser=false;
	if ($uname==$cuser) $curuser=true;
	$n=$uname;
}

$userCardData = \cardbase\get_user_cardinfo($n);
$user_cards = $userCardData['cardlist'];;
$card_energy = $userCardData['cardenergy'];
$cardChosen = $userCardData['cardchosen'];

$pname = $_REQUEST["packName"];
if ($pname!="") {
	if (\cardbase\in_card_pack($pname)) {
		$pack = \cardbase\get_card_pack($pname);
		
		$energy_recover_rate = \cardbase\get_energy_recover_rate($user_cards, $gold);
		
		$unlock_cards = array();
		foreach ($user_cards as $card_index) {
			if (array_key_exists($card_index, $pack))
				$unlock_cards[$card_index]=$pack[$card_index];
		}
		
		$lock_cards = array_diff_key($pack, $unlock_cards);
		$unlock_cards = \cardbase\card_sort($unlock_cards); $lock_cards = \cardbase\card_sort($lock_cards);
		$pack_num = count($pack);
		$unlock_num = count($unlock_cards);
	}
}
include template('card_book');

