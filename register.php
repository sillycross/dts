<?php

define('CURSCRIPT', 'register');
define('LOAD_CORE_ONLY', TRUE);
require './include/common.inc.php';
include './gamedata/banlist.list';

if(isset($cuser) && isset($cpass)){
	gexit($_ERROR['logged_in'],__file__,__line__);
}
if(!isset($cmd)){
	//$ustate = 'register';
	$icon = 0;
	$gender = 'm';
	$iconarray = get_iconlist();
	$select_icon = 0;
	$motto = $criticalmsg = $killmsg = $lastword = '';
	include template('register');
}elseif($cmd = 'post_register'){
	//$ustate = 'register';
	$gamedata = Array();
	$name_check = name_check($username);
	$pass_check = pass_check($npass,$rnpass);
	$onlineip = real_ip();
	
	if($name_check!='name_ok'){
		$gamedata['innerHTML']['info'] = $_ERROR[$name_check];
	}elseif($pass_check!='pass_ok'){
		$gamedata['innerHTML']['info'] = $_ERROR[$pass_check];
	}elseif(preg_match($iplimit,$onlineip)){
		$gamedata['innerHTML']['info'] = $_ERROR['ip_banned'];
	}else{
		$userdata = fetch_udata_by_username($username,'uid');
		if(!empty($userdata)) {
			$gamedata['innerHTML']['info'] = $_ERROR['name_exists'];
		}else{//现在开始注册
			$groupid = 1;
			$credits = 0;
			$gold = 100;
			$password = create_cookiepass($npass);
			$stored_password = create_storedpass($username, $password);
			$i_udata = array(
				'username' => $username,
				'password' => $stored_password,
				'alt_pswd' => 1,
				'groupid' => $groupid,
				'ip' => $onlineip,
				'credits' => $credits,
				'gold' => $gold,
				'gender' => $gender,
				'icon' => $icon,
				'motto' => $motto,
				'killmsg' => $killmsg,
				'lastword' => $lastword,
				'card_data' => gencode(Array(0=>Array('cardid'=>0,'cardenergy'=>0))),//因为注册是只载入核心模块的，只能这么干
//				'cardlist' => '0',
			);
			$result = insert_udata($i_udata);
			if($result){
				$gamedata['innerHTML']['info'] = $_INFO['reg_success'];
				$ustate = 'check';
				gsetcookie('user',$username);
				gsetcookie('pass',$password);
			}else{
				$gamedata['innerHTML']['info'] = $_ERROR['db_failure'];
				$gamedata['innerHTML']['info'] .= ob_get_contents();
			}
		}
	}
	if(!empty($ustate) && $ustate == 'check'){
		$gamedata['innerHTML']['postreg'] = '<input type="button" value="返回游戏首页" onclick="window.location.href=\'index.php\'">';
		if(isset($error)){$gamedata['innerHTML']['error'] = $error;}
		ob_clean();
		$jgamedata = gencode($gamedata);
		echo $jgamedata;
		ob_end_flush();
	}else{
		ob_clean();
		if(isset($error)){$gamedata['innerHTML']['error'] = $error;}
		$jgamedata = gencode($gamedata);
		echo $jgamedata;
		ob_end_flush();
	}
}

?>