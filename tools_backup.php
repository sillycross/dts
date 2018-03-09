<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

define('IN_GAME', TRUE);
define('GAME_ROOT', '');
$server_config =  './include/modules/core/sys/config/server.config.php';
include $server_config;

include './include/global.func.php';
check_authority();
$db = init_dbstuff();

dump_db("{$tablepre}users");
dump_db("{$tablepre}history");
dump_db("{$tablepre}shistory");
dump_db("{$tablepre}messages");
echo 'done.';

function dump_db($dbname){
	global $db;
	$result = $db->query("SELECT * FROM $dbname");
	while($r = $db->fetch_array($result)){
		writeover($dbname.'.dat', json_encode($r, JSON_UNESCAPED_UNICODE)."\n", 'ab+');
	}
	echo $dbname.' backuped.<br>';
}