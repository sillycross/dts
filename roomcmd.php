<?php

error_reporting(0);
ignore_user_abort(1);
define('CURSCRIPT', 'roomcmd');
define('IN_GAME', true);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');
require GAME_ROOT.'./include/global.func.php';
$url = url_dir().'command.php';
$context = array('page'=>'command_roomcmd');
foreach($_POST as $pkey => $pval){
	$context[$pkey] = $pval;
}
foreach($_GET as $pkey => $pval){
	$context[$pkey] = $pval;
}
if(isset($_GET['command'])) $context['not_ajax'] = 1;//蛋疼
$cookies = array();
foreach($_COOKIE as $ckey => $cval){
	if(strpos($ckey,'user')!==false || strpos($ckey,'pass')!==false) $cookies[$ckey] = $cval;
}
$roomcmdinfo = curl_post($url, $context, $cookies);
if(strpos($roomcmdinfo, 'redirect')===0){
	list($null, $url) = explode(':',$roomcmdinfo);
	header('Location: '.$url);
	exit();
}
if(strpos($roomcmdinfo,'<head>')===false){
	$d_roomcmdinfo = gdecode($roomcmdinfo,1);
	if(is_array($d_roomcmdinfo) && isset($d_roomcmdinfo['url']) && 'error.php' == $d_roomcmdinfo['url']){
		gexit($d_roomcmdinfo['errormsg'],__file__,__line__);
	}
}

echo $roomcmdinfo;

/* End of file roomcmd.php */
/* Location: /roomcmd.php */