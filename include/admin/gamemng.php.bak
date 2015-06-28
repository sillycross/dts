<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
$gamemng_cmd_list = Array(
	'pcmng' => 5,
	'npcmng' => 5,
	'shopmng' => 0,
	'mapmng' => 0,
	'mapitemmng' => 0,
	'chatmng' => 0,
	'newsmng' => 0,
	'wthmng' => 4,
	'gameinfomng' => 5,
	'antiAFKmng' => 0,
	'sttimemng' => 3,
);
if($command == 'gamemng'){$command = 'menu';}
if(in_array($command,$gamemng_cmd_list) && $gamemng_cmd_list[$command] > 0 && $mygroup >= $gamemng_cmd_list[$command]){
	if($command == 'pcmng') {
		include_once GAME_ROOT.'./include/admin/pcmng.php';
	} elseif($command == 'npcmng') {
		include_once GAME_ROOT.'./include/admin/npcmng.php';
	} elseif($command == 'shopmng') {
	} elseif($command == 'mapmng') {
	} elseif($command == 'mapitemmng') {
		include_once GAME_ROOT.'./include/admin/mapitemmng.php';
	} elseif($command == 'chatmng') {
	} elseif($command == 'newsmng') {
	} elseif($command == 'wthmng') {
		include_once GAME_ROOT.'./include/admin/wthmng.php';
	} elseif($command == 'gameinfomng') {
		include_once GAME_ROOT.'./include/admin/gameinfomng.php';
	} elseif($command == 'infomng') {
		include_once GAME_ROOT.'./include/admin/infomng.php';
	} elseif($command == 'antiAFKmng') {
		include_once GAME_ROOT.'./include/admin/antiAFKmng.php';
	} elseif($command == 'sttimemng') {
		include_once GAME_ROOT.'./include/admin/sttimemng.php';
	}
}elseif($mygroup < $gamemng_cmd_list[$command]){
	$cmd_info = $_ERROR['no_power'];
}

include template('admin_gamemng_menu');
?>