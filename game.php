<?php

define('CURSCRIPT', 'game');
define('IN_GAME', true);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');

require GAME_ROOT.'./include/global.func.php';
include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';

if(isset($_POST['mode']) && $_POST['mode'] == 'quit') {
	gsetcookie('user','');
	gsetcookie('pass','');
	header("Location: index.php");
	exit();
}

$url = url_dir().'command.php';
$context = array('page'=>'command_game');
foreach($_POST as $pkey => $pval){
	$context[$pkey] = $pval;
}
$cookies = array();
foreach($_COOKIE as $ckey => $cval){
	if(strpos($ckey,'user')!==false || strpos($ckey,'pass')!==false) $cookies[$ckey] = $cval;
}
$gameinfo = send_post($url, $context, $cookies);
if(strpos($gameinfo,'<head>')===false){
	$d_gameinfo = gdecode($gameinfo,1);
	if(is_array($d_gameinfo) && isset($d_gameinfo['url']) && 'error.php' == $d_gameinfo['url']){
		gexit($d_gameinfo['errormsg'],__file__,__line__);
	}
}
if(strpos($gameinfo, 'redirect')===0){
	list($null, $url) = explode(':',$gameinfo);
	header('Location: '.$url);
	return;
}
echo $gameinfo;

/* End of file game.php */
/* Location: /game.php */