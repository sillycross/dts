<?php
if(!defined('IN_GAME')) {
	exit('Access Denied');
}

eval(import_module('sys','player','map','input'));
if(!$cuser||!$cpass) { 
	gexit($_ERROR['no_login'],__file__,__line__);
	return;
} 
$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
if(!$db->num_rows($result)) { 
	echo 'redirect:index.php';
	return;
}

$pdata = $db->fetch_array($result);
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

\player\load_playerdata($pdata);
\player\init_playerdata();
extract($pdata);

if($hp<=0 || $state>=10) {
	$result = $db->query("SELECT lastword FROM {$gtablepre}users WHERE username='$name'");
	$motto = $db->result($result,0);
	$dtime = date("Y年m月d日H时i分s秒",$endtime);
	if($bid) {
		$result = $db->query("SELECT name FROM {$tablepre}players WHERE pid='$bid'");
		if($db->num_rows($result)) { $kname = $db->result($result,0); }
	}
}

$noticelog = '';

if(isset($ecommand) && 'nextgamevars' == $ecommand){
	if($groomid){
		$noticelog = '只有标准房才能修改下一局模式！<br>';
	}elseif(!in_array($winmode, array(2,3,5,7)) || $cuser != $winner || ($state != 5 && $state != 6)){
		$noticelog = '你不是获胜者，不能修改下一局模式！<br>';
	}else{
		$ngamevars = array('next_gametype' => (int)$ngametype);
		$notice = \sys\user_set_gamevars($ngamevars)['notice'];
		foreach($notice as $ns){
			$noticelog .= $ns.'<br>';
		}
	}
	$next_gamevars_display = \sys\user_display_gamevars_setting();
	$gamedata = array('innerHTML' => array('nextgamevars' => $next_gamevars_display));
	$jgamedata=gencode($gamedata);
	ob_clean();
	echo $jgamedata;
}else{
	$next_gamevars_display = \sys\user_display_gamevars_setting();
	include template('end');
}

/* End of file command_end.php */
/* Location: /include/pages/command_end.php */