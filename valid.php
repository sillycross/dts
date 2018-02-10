<?php

define('CURSCRIPT', 'valid');
define('IN_GAME', true);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');

require GAME_ROOT.'./include/global.func.php';
include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';

$url = url_dir().'command.php';
$context = array('page'=>'command_valid');
foreach($_POST as $pkey => $pval){
	$context[$pkey] = $pval;
}
$cookies = array();
foreach($_COOKIE as $ckey => $cval){
	if(strpos($ckey,'user')!==false || strpos($ckey,'pass')!==false) $cookies[$ckey] = $cval;
}
$validinfo = curl_post($url, $context, $cookies);
if(strpos($validinfo, 'redirect')===0){
	list($null, $url) = explode(':',$validinfo);
	header('Location: '.$url);
	exit();
}
if(strpos($validinfo,'<head>')===false){
	$d_validinfo = gdecode($validinfo,1);
	if(is_array($d_validinfo) && isset($d_validinfo['url']) && 'error.php' == $d_validinfo['url']){
		gexit($d_validinfo['errormsg'],__file__,__line__);
	}
}
echo $validinfo;

/* End of file valid.php */
/* Location: /valid.php */