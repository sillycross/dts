<?php

define('CURSCRIPT', 'winner');
define('IN_GAME', true);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');
require GAME_ROOT.'./include/global.func.php';
		
$url = 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,-10).'command.php';
$context = array('page'=>'command_winner');
$winnerinfo = send_post($url, $context);
echo $winnerinfo;

/* End of file winner.php */
/* Location: /winner.php */