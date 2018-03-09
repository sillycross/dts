<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

define('IN_GAME', TRUE);
define('GAME_ROOT', '');
$server_config =  './include/modules/core/sys/config/server.config.php';
include $server_config;

include './include/global.func.php';
check_authority();
$db = init_dbstuff();

dump_db("{$gtablepre}users");
dump_db("{$gtablepre}history");
dump_db("{$gtablepre}shistory");
dump_db("{$gtablepre}messages");
echo 'done.';

function dump_db($dbname){
	global $db;
	$result = $db->query("SELECT * FROM $dbname");
	while($r = $db->fetch_array($result)){
		writeover($dbname.'.dat', json_encode($r, JSON_UNESCAPED_UNICODE)."\n", 'ab+');
	}
	echo $dbname.' backuped.<br>';
}