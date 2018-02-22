<?php
if(!defined('IN_GAME')) {
	exit('Access Denied');
}

eval(import_module('sys','player','map'));

if(!$cuser||!$cpass) { 
	gexit($_ERROR['no_login'],__file__,__line__);
	return;
} 

$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");

if(!$db->num_rows($result)) 
{ 
	echo 'redirect:valid.php';
	return;
}

$pdata = $db->fetch_array($result);

//判断是否密码错误
if($pdata['pass'] != $cpass) {
	$tr = $db->query("SELECT `password` FROM {$gtablepre}users WHERE username='$cuser'");
	$tp = $db->fetch_array($tr);
	$password = $tp['password'];
	include_once './include/user.func.php';
	if(pass_compare($cuser, $cpass, $password)) {
		$db->query("UPDATE {$tablepre}players SET pass='$password' WHERE name='$cuser'");
	} else {
		gexit($_ERROR['wrong_pw'],__file__,__line__);
		return;
	}
}

if($gamestate == 0) {
	echo 'redirect:end.php';
	return;
}

\player\load_playerdata(\player\fetch_playerdata($cuser));

\player\init_playerdata();
\player\parse_interface_profile();

if(in_array($state, array(4,5,6))) {
	echo 'redirect:end.php';
	return;
}

$log = '';
//读取聊天信息
//$chatdata = array_merge(getchat(0,$teamID,$pid),\sys\getnews(0));
$chatdata = getchat(0,$teamID,$pid);
//生成进行状况id但是不马上拉取（非常耗时，用ajax完成）
$result = $db->query("SELECT nid FROM {$tablepre}newsinfo ORDER BY nid LIMIT $newslimit");
$lastnid = $db->fetch_array($result)['nid'];
$chatdata['lastnid'] = $lastnid-1;
$nidtmp = 'nid'.($lastnid);
$chatdata['news'] = array('<li id="'.$nidtmp.'" class="red">正在拉取进行状况…</li>');

$hp_backup_temp=$hp;
$player_dead_flag_backup_temp=$player_dead_flag;

if ($hp<=0 || $player_dead_flag)
{
	player\pre_act();
	player\post_act();
}

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

/* End of file command_game.php */
/* Location: /include/pages/command_game.php */