<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
header('Content-Type: text/HTML; charset=utf-8');
header( 'Content-Encoding: none; ' );
define('IN_GAME', TRUE);
define('GAME_ROOT', '');
$server_config =  './include/modules/core/sys/config/server.config.php';
include $server_config;

include './include/global.func.php';
check_authority();
$db = init_dbstuff();
$r = $db->query("DELETE FROM {$gtablepre}users WHERE lastgame<2230 AND credits<1000");

var_dump($r);
