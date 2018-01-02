<?php

define('CURSCRIPT', 'end');
define('IN_GAME', true);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');

require GAME_ROOT.'./include/global.func.php';
include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';

$url = url_dir().'command.php';
$context = array('page'=>'command_end');
foreach($_POST as $pkey => $pval){
	$context[$pkey] = $pval;
}
$cookies = array();
foreach($_COOKIE as $ckey => $cval){
	if(strpos($ckey,'user')!==false || strpos($ckey,'pass')!==false) $cookies[$ckey] = $cval;
}
$endinfo = curl_post($url, $context, $cookies);
if(strpos($endinfo,'<head>')===false){
	$d_endinfo = gdecode($endinfo,1);
	if(is_array($d_endinfo) && isset($d_endinfo['url']) && 'error.php' == $d_endinfo['url']){
		gexit($d_endinfo['errormsg'],__file__,__line__);
	}
}
echo $endinfo;

/* End of file end.php */
/* Location: /end.php */