<?php

define('CURSCRIPT', 'winner');
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');
define('NO_MOD_LOAD', TRUE);	//不在common.inc载入任何模块
define('NO_SYS_UPDATE', TRUE);
require GAME_ROOT.'./include/common.inc.php';
		
$url = 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,-10).'command.php';
$context = array('page'=>'command_winner');
$winnerinfo = send_post($url, $context);
echo $winnerinfo;

/* End of file winner.php */
/* Location: /winner.php */