<?php

define('CURSCRIPT', 'admin');

require './include/common.inc.php';
define('IN_ADMIN', TRUE);
require GAME_ROOT.'./gamedata/admincfg.php';
require GAME_ROOT.'./include/admin/admin.lang.php';

$admin_cmd_list = Array(
	'configmng' => 9,
	'systemmng' => 7,
	'gamecfgmng' => 7,
	'gmlist' => 9,
	'urlist' => 6,
	'banlistmng' => 6,
	'gamecheck' => 2,
	'pcmng' => 5,
	'npcmng' => 5,
	'gameinfomng' => 5,
	'antiAFKmng' => 4,
);

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); }
$result = $db->query("SELECT * FROM {$tablepre}users WHERE username='$cuser'");
if(!$db->num_rows($result)) { gexit($_ERROR['login_check'],__file__,__line__); }
$udata = $db->fetch_array($result);
if($udata['password'] != $cpass) { gexit($_ERROR['wrong_pw'], __file__, __line__); }
elseif(($udata['groupid'] <= 1)&&($cuser!==$gamefounder)) { gexit($_ERROR['no_admin'], __file__, __line__); }

if($cuser===$gamefounder){$mygroup=10;}
else{$mygroup = $udata['groupid'];}

$showdata = $cmd_info = false;
if($mode == 'admin_menu' && in_array($command, array_keys($admin_cmd_list))) {//进入子菜单的指令
	if($mygroup >= $admin_cmd_list[$command]){
		include_once GAME_ROOT."./include/admin/{$command}.php";
		$showdata = ob_get_contents();
	}else{
		$cmd_info = $_ERROR['no_power'];
	}
	
} elseif(in_array($mode, array_keys($admin_cmd_list))) {//子菜单内指令
	if($mygroup >= $admin_cmd_list[$mode]){
		include_once GAME_ROOT."./include/admin/{$mode}.php";
		$showdata = ob_get_contents();
	}else{
		$cmd_info = $_ERROR['no_power'];
	}
} elseif(!empty($mode)) {
	$cmd_info = $_ERROR['wrong_adcmd'];
}
ob_clean();
include template('admin');
ob_end_flush();


function adminlog($op,$an1='',$an2='',$an3=''){
	global $now,$cuser;
	$alfile = GAME_ROOT.'./gamedata/adminlog.php';
	if($op){
		$aldata = "$now,$cuser,$op,$an1,$an2,$an3,\n";
		writeover($alfile,$aldata,'ab+');
	}
	return;
}
function getstart($start = 0,$mode = ''){
	global $showlimit;
	$start = (int)$start;
	if($mode == 'up') {
		$start -= $showlimit;
		$start = $start <= 0 ? 0 : $start;
	} elseif($mode == 'down') {
		$start += $showlimit;
	} elseif($mode == 'ref') {
		$start = 0;
	} else {
		$start = $start ? $start : 0;
	}
	return $start;
}
function setconfig($string) {
	if(!get_magic_quotes_gpc()) {
		$string = str_replace('\'', '\\\'', $string);
	} else {
		$string = str_replace('\"', '"', $string);
	}
	return $string;
}

function astrfilter($str) {
	if(is_array($str)) {
		foreach($str as $key => $val) {
			$str[$key] = astrfilter($val);
		}
	} else {
		$str = str_replace(Array('eval'),'',$str);//屏蔽会造成困扰的关键字;		
	}
	return $str;
}
?>