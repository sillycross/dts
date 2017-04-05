<?php

define('CURSCRIPT', 'game');
require './include/common.inc.php';

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); } 
if($mode == 'quit') {

	gsetcookie('user','');
	gsetcookie('pass','');
	header("Location: index.php");
	exit();

}
$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
if(!$db->num_rows($result)) { header("Location: valid.php");exit(); }

$pdata = $db->fetch_array($result);
if($pdata['pass'] != $cpass) {
	$tr = $db->query("SELECT `password` FROM {$gtablepre}users WHERE username='$cuser'");
	$tp = $db->fetch_array($tr);
	$password = $tp['password'];
	if($password == $cpass) {
		$db->query("UPDATE {$tablepre}players SET pass='$password' WHERE name='$cuser'");
	} else {
		gexit($_ERROR['wrong_pw'],__file__,__line__);
	}
}

if($gamestate == 0) {
	header("Location: end.php");exit();
}

\player\load_playerdata(\player\fetch_playerdata($cuser));

\player\init_playerdata();
\player\init_profile();

if($state == 4) {
	header("Location: end.php");exit();
}

$log = '';
//读取聊天信息
$chatdata = getchat(0,$teamID,$pid);

$hp_backup_temp=$hp;
$player_dead_flag_backup_temp=$player_dead_flag;

if ($hp<=0 || $player_dead_flag)
{
	player\pre_act();
	player\post_act();
}

//var_dump($itm3);
if($hp <= 0){
	$dtime = date("Y年m月d日H时i分s秒",$endtime);
	$kname='';
	if($bid) {
		$result = $db->query("SELECT name FROM {$tablepre}players WHERE pid='$bid'");
		if($db->num_rows($result)) { $kname = $db->result($result,0); }
	}
	$mode = 'death';
} elseif($state ==1 || $state == 2 || $state == 3){
	$mode = 'rest';
} elseif($itms0){
	$mode = 'itemmain';
} else {
	$mode = 'command';
}

player\prepare_initial_response_content();

include template('game');

if ($hp!=$hp_backup_temp || $player_dead_flag!=$player_dead_flag_backup_temp)
{
	\player\update_sdata();
	\player\player_save($sdata);
}

?>