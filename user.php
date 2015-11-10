<?php

define('CURSCRIPT', 'user');

require './include/common.inc.php';
require './include/user.func.php';

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); }

$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$cuser'");
if(!$db->num_rows($result)) { gexit($_ERROR['login_check'],__file__,__line__); }
$udata = $db->fetch_array($result);
if($udata['password'] != $cpass) { gexit($_ERROR['wrong_pw'], __file__, __line__); }
if($udata['groupid'] <= 0) { gexit($_ERROR['user_ban'], __file__, __line__); }

//write card json
//$objfile = GAME_ROOT."./gamedata/templates/{$templateid}_$file.tpl.php";
require config('card',$gamecfg);

$jfile=GAME_ROOT."./gamedata/cache/card.json";
$cdfile=GAME_ROOT."./gamedata/cache/card_1.php";
if ((!file_exists($jfile)) || (filemtime($cdfile) > filemtime($jfile))){
	if(!$fp = fopen($jfile, 'w')) {
		gexit("咕咕咕");
	}
	$jdesc=json_encode($carddesc,JSON_UNESCAPED_UNICODE);
	flock($fp, 2);
	fwrite($fp, $jdesc);
	fclose($fp);
}

if(!isset($mode)){
	$mode = 'show';
}

if($mode == 'edit') {
	$gamedata=Array();$gamedata['innerHTML']['info'] = '';
	if($opass && $npass && $rnpass){
		$pass_right = true;
		$pass_check = pass_check($npass,$rnpass);
		if($pass_check!='pass_ok'){
			$gamedata['innerHTML']['info'] .= $_ERROR[$pass_check].'<br />';
			$pass_right = false;
		}
		$opass = md5($opass);
		$npass = md5($npass);
		if($opass != $udata['password']){
			$gamedata['innerHTML']['info'] .= $_ERROR['wrong_pw'].'<br />';
			$pass_right = false;
		}
		if($pass_right){
			gsetcookie('pass',$npass);
			$passqry = "`password` ='$npass',";
			$gamedata['innerHTML']['info'] .= $_INFO['pass_success'].'<br />';
		}else{
			$passqry = '';
			$gamedata['innerHTML']['info'] .= $_INFO['pass_failure'].'<br />';
		}
	}else{
		$passqry = '';
		$gamedata['innerHTML']['info'] .= $_INFO['pass_failure'].'<br />';
	}
	
	$carr = explode('_',$udata['cardlist']);
	$cflag=0;
	foreach ($carr as $val){
		if ($val==$card){
			$cflag=true;
			break;
		}
	}
	if (!$cflag) $card=0;
	
	$db->query("UPDATE {$gtablepre}users SET gender='$gender', icon='$icon',{$passqry}motto='$motto',  killmsg='$killmsg', lastword='$lastword' ,card='$card' WHERE username='$cuser'");
	if($db->affected_rows()){
		$gamedata['innerHTML']['info'] .= $_INFO['data_success'];
	}else{
		$gamedata['innerHTML']['info'] .= $_INFO['data_failure'];
	}
	
	$gamedata['value']['opass'] = $gamedata['value']['npass'] = $gamedata['value']['rnpass'] = '';
	if(isset($error)){$gamedata['innerHTML']['error'] = $error;}
	ob_clean();
	$jgamedata = base64_encode(gzencode(compatible_json_encode($gamedata)));
	echo $jgamedata;
	ob_end_flush();
	
} else {
	//$ustate = 'edit';
	extract($udata);
	$iconarray = get_iconlist($icon);
	$select_icon = $icon;
	if ($cardlist==""){
		$cardlist="0";
		$db->query("UPDATE {$gtablepre}users SET cardlist='$cardlist' WHERE username='$username'");
	}
	$carr = explode('_',$cardlist);
	$clist = Array();
	$cad=$card;
	
	foreach($carr as $key => $val){
		$clist[$key] = $val;
	}
	include template('user');
	
}

?> 