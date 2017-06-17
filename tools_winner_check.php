<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

define('IN_GAME', TRUE);
define('GAME_ROOT', '');
$server_config =  './include/modules/core/sys/config/server.config.php';
include $server_config;

include './include/global.func.php';
check_authority();
$db = init_dbstuff();
$r = $db->query("SELECT * FROM {$gtablepre}winners WHERE name!=''");
$arr = array();
$i = 0;
while($rr = $db->fetch_array($r)){
	$arr[strtoupper($rr['name'])][] = array(
		'gid' => $rr['gid'],
		'wmode' => $rr['wmode'],
		'gametype' => $rr['gametype']
	);
	$i++;
}
writeover('winnercheck.dat', gencode($arr));
echo $i.'logs checked.';
//$db->query("ALTER TABLE {$gtablepre}rooms ADD `roomtype` tinyint unsigned NOT NULL DEFAULT 0");
