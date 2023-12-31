<?php

define('CURSCRIPT', 'admin');

require './include/common.inc.php';
define('IN_ADMIN', TRUE);
require GAME_ROOT.'./gamedata/admincfg.php';
require GAME_ROOT.'./include/admin/admin.lang.php';

$udata = udata_check();

$admin_cmd_list = Array(
	'configmng' => 7,
	'globalgamemng' => 6,
	'gamecfgmng' => 6,
	'gmlist' => 9,
	'urlist' => 8,
	'banlistmng' => 5,
	'gamecheck' => 2,
	'adminlogcheck' => 2,
	'pcmng' => 5,
	'npcmng' => 5,
	'gameinfomng' => 5,
	'antiAFKmng' => 4,
	'rankclear' => 9,
	'roomclose' => 5,
	'dbmng' => 9,
);

if(($udata['groupid'] <= 1)&&($cuser!==$gamefounder)) { gexit($_ERROR['no_admin'], __file__, __line__); }

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
	$alfile = GAME_ROOT.'./gamedata/adminlog_nf.php';
	$checkstr = "<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>\r\n";
	if(!file_exists($alfile)) {
		file_put_contents($alfile, $checkstr);
	}
	else {
		$check = file_get_contents($alfile, 0, NULL, 0, 30);
		if(strpos($checkstr, $check)!==0) {
			$cont = file_get_contents($alfile);
			file_put_contents($alfile, $checkstr.$cont);
		}
	}
	if($op){
		$aldata = "$now,$cuser,$op,$an1,$an2,$an3,\n";
		file_put_contents($alfile,$aldata,FILE_APPEND);
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