<?
error_reporting(E_ERROR | E_WARNING | E_PARSE);

define('IN_GAME', TRUE);
define('GAME_ROOT', '');
$server_config =  './include/modules/core/sys/config/server.config.php';
include $server_config;
include './include/db_'.$database.'.class.php';
$db = new dbstuff;
$db->connect($dbhost, $dbuser, $dbpw,$dbname);
$db->query("ALTER TABLE {$gtablepre}rooms ADD `roomtype` tinyint unsigned NOT NULL DEFAULT 0");