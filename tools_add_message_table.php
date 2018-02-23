<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

define('IN_GAME', TRUE);
define('GAME_ROOT', '');
$server_config =  './include/modules/core/sys/config/server.config.php';
include $server_config;

include './include/global.func.php';
check_authority();
$db = init_dbstuff();
$install_db = file_get_contents('./install/bra.install.sql');
preg_match('/DROP TABLE IF EXISTS `bra_messages`[\s\S]+ENGINE=InnoDB DEFAULT CHARSET=utf8;/s', $install_db, $matches);
$query = str_replace('bra_',$gtablepre,$matches[0]);
$db->queries($query);
preg_match('/DROP TABLE IF EXISTS `bra_del_messages`[\s\S]+ENGINE=InnoDB DEFAULT CHARSET=utf8;/s', $install_db, $matches);
$query = str_replace('bra_',$gtablepre,$matches[0]);
$db->queries($query);
echo 'done';