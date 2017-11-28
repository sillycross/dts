<?php

define('CURSCRIPT', 'valid');
define('IN_GAME', true);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');

require GAME_ROOT.'./include/global.func.php';

$url = url_dir().'command.php';
$context = array('page'=>'command_valid');
foreach($_POST as $pkey => $pval){
	$context[$pkey] = $pval;
}
$cookies = array();
foreach($_COOKIE as $ckey => $cval){
	if(strpos($ckey,'user')!==false || strpos($ckey,'pass')!==false) $cookies[$ckey] = $cval;
}
$validinfo = send_post($url, $context, $cookies);
if(strpos($validinfo, 'redirect')===0){
	list($null, $url) = explode(':',$validinfo);
	header('Location: '.$url);
	return;
}
echo $validinfo;

/* End of file valid.php */
/* Location: /valid.php */