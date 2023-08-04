<?php

define('CURSCRIPT', 'cardbook');

require './include/common.inc.php';

eval(import_module('cardbase'));

$_REQUEST = gstrfilter($_REQUEST);

if (empty($_REQUEST["playerID"])) {
	$udata = udata_check();
	
	$n=$cuser;
	extract($udata);
	$curuser=true;
} else {
	$uname=urldecode($_REQUEST["playerID"]);
	$udata = fetch_udata_by_username($uname);
	if(empty($udata)) { gexit($_ERROR['user_not_exists'],__file__,__line__); }
	extract($udata);
	$curuser=false;
	if ($uname==$cuser) $curuser=true;
	$n=$uname;
}

//刷新卡包cardindex
\cardbase\parse_card_index();
//刷新卡片获得方式
\cardbase\parse_card_gaining_method();
//读取卡片获得方式
$cgmfile = GAME_ROOT.'./gamedata/cache/card_gaining_method.config.php';
if(file_exists($cgmfile)) include $cgmfile; 

$userCardData = \cardbase\get_user_cardinfo($n);
$user_cards = $userCardData['cardlist'];
$card_energy = $userCardData['cardenergy'];
$cardChosen = $userCardData['cardchosen'];
$packlist = \cardbase\pack_filter($packlist);

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
$d_achievements = \achievement_base\decode_achievements($udata);
$card_achieved_list = array();
//全能骑士成就特判
if(!empty($d_achievements['326'])) $card_achieved_list = $d_achievements['326'];
include template('card_book');

/* End of file cardbook.php */
/* Location: /cardbook.php */