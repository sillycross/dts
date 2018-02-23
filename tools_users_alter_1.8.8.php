<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

define('IN_GAME', TRUE);
define('GAME_ROOT', '');
$server_config =  './include/modules/core/sys/config/server.config.php';
include $server_config;

include './include/global.func.php';
check_authority();
$db = init_dbstuff();
$db->query("ALTER TABLE {$gtablepre}users ADD `u_templateid` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER cardenergylastupd");
echo 'done.';